<?php

namespace NanoDepo\Taberna\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use NanoDepo\Taberna\Database\Factories\ProductFactory;
use NanoDepo\Taberna\Database\Factories\VariantFactory;
use NanoDepo\Taberna\Models\Attribute;
use NanoDepo\Taberna\Models\Brand;
use NanoDepo\Taberna\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->lumina();
        $this->celestial();
        $this->geode();
        $this->ephemeral();
        $this->alchemists();
        $this->mechanica();
    }

    public function lumina(): void
    {
        $category = Category::query()->where('name', 'Lumina Textiles')->first();
        $brand = Brand::query()->where('slug', 'nanodepo')->first();
        $attribute = Attribute::query()->with('options')->where('name', 'Color')->first();

        $category->attributes()->attach($attribute->id, [
            'default_value' => $attribute->options->first()->name,
            'option_id' => $attribute->options->first()->id,
            'is_required' => true,
        ]);

        $product = ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Glimmer-Thread Spool',
            'intro' => 'A spool of thread spun from pure moonlight. It glows softly in the dark, perfect for enchanting embroidery or mending magical garments. It\'s weightless, strong, and guaranteed to never tangle. A vital tool in tailoring magic.',
            'description' => 'This thread is harvested on the Silent Peaks, where moonbeams freeze in the morning mist. It is impossibly strong, yet softer than silk to the touch. It is said that clothes sewn with this thread bring a sense of calm to the wearer and protect from bad dreams. Its glow is faint, like a cluster of distant stars, and it pulses gently in time with your own heartbeat. An ideal choice for enchanters, arcane tailors, and anyone who weaves a bit of magic into their work. Each spool holds enough thread to embroider an entire constellation.',
            'price' => 1590,
            'discount' => 120,
            'quantity' => 5,
            'has_variants' => true,
            'is_main' => true,
        ])->create();

        $product->attributes()->attach($attribute->id, [
            'value' => $attribute->options->first()->name,
            'option_id' => $attribute->options->first()->id,
        ]);

        foreach ($attribute->options as $option) {
            $variant = (new VariantFactory)->product($product)->create();
            $variant->options()->attach($option->id);
        }

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Nebula-Woven Cloak',
            'intro' => 'A cloak that seems to hold a swirling galaxy within its fabric. Its colors slowly shift and shimmer as if alive. It is surprisingly warm despite being utterly weightless. A true centerpiece for any ceremonial or arcane attire.',
            'description' => 'Woven on celestial looms from the raw, chaotic beauty of a newborn nebula, each of these cloaks is entirely unique. Its pattern is a snapshot of a real cosmic event, frozen in time. The fabric is weightless and provides warmth not from insulation, but by radiating a gentle, cosmic energy. The wise say that by staring into its depths, one can see echoes of the past or hints of the future. A priceless treasure for court astronomers, oracles, and those who rule by looking to the stars.',
            'price' => 135,
            'discount' => 0,
            'quantity' => 22,
        ])->create();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Whispering Scarf',
            'intro' => 'This scarf is woven from the echoes of a silent forest. It gently whispers forgotten lullabies and comforting words that only the wearer can hear. Incredibly soft, it provides a feeling of warmth and safety on any journey.',
            'description' => 'A unique garment crafted from Echo-Weave, a material that captures ambient sounds during its creation. These scarves are woven in the Valley of Silent Winds, where ancient lullabies still linger on the air. Their whispers are soothing, helping to calm anxiety and ease one into a peaceful sleep. The scarf itself is as soft as a moth\'s wing and subtly shifts its hue to match the wearer\'s mood. The perfect companion for an anxious traveler, an insomniac, or anyone in need of quiet comfort in a noisy world.',
            'price' => 739,
            'discount' => 40,
            'quantity' => 10,
        ])->create();
    }

    public function celestial(): void
    {
        $category = Category::query()->where('name', 'Celestial Armory')->first();
        $brand = Brand::query()->where('slug', 'nanodepo')->first();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Meteorite Ore Ingot',
            'intro' => 'A dense ingot of raw, unrefined meteorite ore, humming with latent cosmic energy. It\'s warm to the touch. Highly sought after by arcane blacksmiths for forging powerful artifacts and enchanted blades.',
            'description' => 'Harvested from the core of a fallen star, this ingot is more than mere metal. It pulses with a deep, cosmic resonance that can be felt by those sensitive to magic. When heated, the metal exhibits strange properties, shifting in color and emitting a low, melodic hum. Blacksmiths who have mastered the art of forging star-iron can create blades that never dull and armor that deflects curses. This is a raw, untamed piece of the heavens itself, awaiting the hand of a master craftsman.',
            'price' => 212,
            'discount' => 0,
            'quantity' => 10,
            'is_main' => true,
        ])->create();
    }

    public function geode(): void
    {
        $category = Category::query()->where('name', 'Geode Gardens')->first();
        $brand = Brand::query()->where('slug', 'abracadabra-inc')->first();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Sunstone Seed',
            'intro' => 'Plant this seed in any soil, and it will grow into a beautiful crystalline formation that emits a warm, gentle light. It requires no water or care, only darkness to allow it to truly shine.',
            'description' => 'A marvel from the Geode Gardens, this is not a plant seed, but a crystalline embryo. Once planted, it draws latent energy from the earth to grow a cluster of Sunstones. The crystals are a warm, honey-gold color and radiate a light as comforting as the morning sun. They remain perpetually warm and will become an eternal source of light for a study, a bedroom, or a dark cavern. It is said their light promotes a sense of calm and encourages creative thoughts.',
            'price' => 840,
            'discount' => 90,
            'quantity' => 100,
        ])->create();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Cryo-Crystal Shard',
            'intro' => 'A shard of crystal that is perpetually cold. It never melts or warms up. Perfect for chilling drinks, preserving rare alchemical ingredients, or for soothing a fevered brow. A simple, profound utility.',
            'description' => 'Harvested from the heart of a winter geode, this crystal shard holds the very essence of absolute cold. It is smooth to the touch but leaves a trail of delicate frost on any surface it rests upon. Unlike ice, it never changes its state, remaining a constant and reliable source of cold. Alchemists use it to stabilize volatile potions, while chefs in exotic realms use it to create instantaneous frozen desserts. A single shard, placed in a container, can keep its contents perfectly preserved for centuries.',
            'price' => 990,
            'discount' => 10,
            'quantity' => 150,
            'is_main' => true,
        ])->create();
    }

    public function ephemeral(): void
    {
        $category = Category::query()->where('name', 'Ephemeral Adornments')->first();
        $brand = Brand::query()->where('slug', 'nanodepo')->first();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Pendant of a Captured Dream',
            'intro' => 'This beautiful pendant holds a single, peaceful dream, swirling within the polished glass. The dream\'s essence gently soothes the wearer, warding off nightmares and anxiety. Carry a piece of pure tranquility with you.',
            'description' => 'Master dream-catchers collect only the most serene and joyful dreams. A single, perfect dream is then encapsulated within this hand-blown sphere of moonlight glass. The swirling colors inside are the dream itself, forever replaying its gentle narrative. Wearing the pendant provides a constant sense of peace and security. It is especially potent at night, creating a protective barrier against unwelcome thoughts and nightmares. The perfect gift for a loved one who deserves a peaceful rest.',
            'price' => 360,
            'discount' => 31,
            'quantity' => 3,
        ])->create();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Ring of Fading Starlight',
            'intro' => 'A simple silver band holding a gem that contains the last light of a dying star. It glows brightest in complete darkness, serving as a reminder that light exists even in the end. A symbol of hope and a guide in the dark.',
            'description' => 'A star\'s last breath is a brilliant flash of light. Star-foragers travel the void to capture this final moment in a specially prepared crystal. The resulting gem, set in a simple ring, holds this fading light forever. In bright light, it appears to be a simple, smoky quartz. But in darkness, it emits a soft, melancholic blue glow—your own tiny star. It is a poignant memento mori, a symbol of hope, and a personal guide for when your path seems darkest.',
            'price' => 1200,
            'discount' => 0,
            'quantity' => 7,
        ])->create();
    }

    public function alchemists(): void
    {
        $category = Category::query()->where('name', 'Alchemist\'s Pantry')->first();
        $brand = Brand::query()->where('slug', 'abracadabra-inc')->first();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Bottled Time',
            'intro' => 'A sealed bottle containing one hour of pure, unused time, which appears as a slow-drifting, golden mist. Its use is a mystery that the owner must discover for themselves. Handle with extreme care.',
            'description' => 'Harvested from temporal eddies found where the fabric of reality is thin, this bottle contains one standard hour, removed from the flow of causality. What can one do with it? Some say breaking the bottle grants you an extra hour in a day—a moment to act while the world is frozen. Others believe it can be used in alchemy to rapidly age or de-age an object. The Alchemist\'s Guild sells it as a pure, conceptual ingredient and does not guarantee any specific outcome. Be careful; you can\'t put time back in the bottle.',
            'price' => 32,
            'discount' => 5,
            'quantity' => 2384,
        ])->create();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Essence of a Forgotten Melody',
            'intro' => 'A vial of shimmering liquid. When uncorked, it releases a beautiful, heartbreaking melody that no one has ever heard before, and no one will ever hear again. A unique, unrepeatable experience for a single use.',
            'description' => 'Sound-smiths travel to the ruins of ancient civilizations and use resonant flasks to capture the "ghosts" of music once played there. This essence contains one such melody. Upon opening the vial, the music will fill the air for several minutes before fading into nothingness forever. Bards use it for inspiration, lovers use it to create a perfect moment, and historians use it hoping to catch a glimpse into the culture of the past. What will your forgotten melody sound like?',
            'price' => 17,
            'discount' => 0,
            'quantity' => 1920,
        ])->create();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Powdered Laughter',
            'intro' => 'A pinch of iridescent powder. When thrown into the air, it dissolves into the sound of pure, unadulterated joy and laughter, instantly lifting the spirits of everyone nearby. Banish gloom with a single pinch!',
            'description' => 'This peculiar substance is created by capturing the joyous peals of laughter from a child\'s birthday party and crystallizing them. The resulting powder glitters with all the colors of the rainbow. A single pinch cast into a room can dispel gloom, ease tension, and break an awkward silence with a wave of infectious, phantom laughter. It is non-addictive, though its effects are highly sought after by stressed monarchs, gloomy wizards, and anyone hosting a less-than-lively party. A small bag contains twenty pinches of pure joy.',
            'price' => 28,
            'discount' => 3,
            'quantity' => 3955,
        ])->create();
    }

    public function mechanica(): void
    {
        $category = Category::query()->where('name', 'Mechanica Anima')->first();
        $brand = Brand::query()->where('slug', 'nanodepo')->first();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Bronze Beetle with Sapphire Eyes',
            'intro' => 'A masterfully crafted clockwork beetle. When wound up, it scurries around, intelligently avoiding obstacles. Its sapphire eyes glow with a faint blue light. A tiny but loyal mechanical companion.',
            'description' => 'A marvel of micro-mechanics from the workshops of Mechanica Anima. This is no mere toy. Its intricate gear-and-spring system allows it to navigate complex environments, while its enchanted sapphire eyes grant it a rudimentary awareness. It is programmed with a single directive: to stay near its owner. It will follow you, hide in your pocket, and can even be trained to carry very small items on its back. It requires winding once every 24 hours. The perfect familiar for a wizard without the patience for a real pet.',
            'price' => 980,
            'discount' => 111,
            'quantity' => 30,
        ])->create();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Silver-Wired Dragonfly',
            'intro' => 'An elegant clockwork dragonfly with wings of gossamer-thin moon-glass. When released, it flutters around the room, mapping its layout, and then dutifully returns to your hand. A silent, tiny scout.',
            'description' => 'This delicate creation is both a work of art and a useful tool. Its body is woven from enchanted silver wire, and its core contains a tiny, self-winding spring. The true magic lies in its wings, made of glass harvested from lunar reflections on a still pond. Release it, and it will silently fly through a building, its "memory" capturing the spatial dimensions of the rooms it passes through. Upon its return, a skilled mechanist can "read" its flight path to create a rough map of an unexplored area.',
            'price' => 440,
            'discount' => 0,
            'quantity' => 44,
        ])->create();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Pocket Orrery of a Lost Galaxy',
            'intro' => 'A complex, handheld brass-and-crystal device. With the flick of a switch, it projects a holographic 3D map of a galaxy that astronomers claim has never existed. A beautiful mystery that fits in the palm of your hand.',
            'description' => 'No one knows who built the first of these, or where the data for the "lost galaxy" comes from. The device itself is a masterpiece. Intricate brass gears cause tiny crystal planets to orbit a central, glowing sunstone. When a specific switch is activated, it uses ambient light to project a stunning, detailed hologram of its galaxy into the air. Is it a fantasy, a map of another dimension, or a record of a galaxy that existed eons ago? An unsolved and captivating mystery for any scholar to ponder.',
            'price' => 1120,
            'discount' => 60,
            'quantity' => 4,
        ])->create();

        ProductFactory::new()->extra([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Compass That Points to \'Home\'',
            'intro' => 'This compass does not point North. Its obsidian needle always points to the place the owner considers their true home. It works across any distance, and even between dimensions. Never lose your way again.',
            'description' => 'A simple-looking compass with a silver casing and a face of polished crystal. The magic is in the needle, carved from the heart of a "lodestone of longing." To attune it, the new owner must hold it and think deeply of the place they feel most safe and loved—their true home. From that moment on, the needle will unwaveringly point in that direction. It has guided lost sailors across oceans and adventurers out of labyrinthine dungeons. It doesn\'t show you the path, but it always shows you the way.',
            'price' => 12650,
            'discount' => 1300,
            'quantity' => 1,
            'is_main' => true,
        ])->create();
    }
}
