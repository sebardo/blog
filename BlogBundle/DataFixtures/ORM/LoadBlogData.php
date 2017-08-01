<?php
namespace BlogBundle\DataFixtures\ORM;

use CoreBundle\DataFixtures\SqlScriptFixture;
use BlogBundle\Entity\Category;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\PostTranslation;
use BlogBundle\Entity\Tag;
use CoreBundle\Entity\Actor;
use BlogBundle\Entity\Comment;
use BlogBundle\Entity\Image;

/*
 * php app/console doctrine:fixtures:load --fixtures=vendor/sebardo/core/BlogBundle/DataFixtures/ORM/LoadBlogData.php
 */
class LoadBlogData extends SqlScriptFixture
{
    /**
     * There two kind of fixtures
     * Bundle fixtures: all info bundle needed
     * Dev fixtures: info for testing porpouse
     */
    public function createFixtures()
    {
        /**
         * Bundle fixtures
         */
        if($this->container->getParameter('core.fixture_bundle_blog')){
            $this->runSqlScript('Translation.sql');
        }
        
        /**
         * Dev fixtures
         */
        if($this->container->getParameter('core.fixtures_dev_blog_blog')){
                
            $actor = $this->getManager()->getRepository('CoreBundle:BaseActor')->findOneByUsername('user');
            $locales = $this->get('core_manager')->getLocales();
            
            $categories = array(
                'Signs', 'Elements', 'Planets', 'Cusps', 'Compatibility', 
            );
            $tags = array(
                'Aries', 'Fire', 'Mars', 'Taurus', 'Earth', 'Moon', 'Gemini', 'Air', 'Mercury', 'Cancer'
            );
            
            $titles = array(
                'es' => 'Aries Signo Solar',
                'en' => 'Aries Sun Sign',
                'de' => 'Widder Sun Sign'
            );
            $shortDesciptions = array(
                'es' => '<p>Aries es el primer signo del zodiaco, y eso es más o menos como Los nacidos bajo este signo verse a sí mismos: en primer lugar. Aries son los líderes de la manada, primero en la fila para que las cosas funcionen. Sea o no todo se hace es otra cuestión, ya que en Aries prefiere iniciar en lugar de en completarse. ¿Tiene un proyecto que necesitan una a poner en marcha? Llame a Aries, por todos los medios. El liderazgo mostrada por Aries es más impresionante, por lo que no se sorprenda si pueden reunir a las tropas contra probabilidades aparentemente insuperables.</p>',
                'en' => "<p>Aries is the first sign of the zodiac, and that's pretty much how those born under this sign see themselves: first. Aries are the leaders of the pack, first in line to get things going. Whether or not everything gets done is another question altogether, for an Aries prefers to initiate rather than to complete. Do you have a project needing a kick-start? Call an Aries, by all means. The leadership displayed by Aries is most impressive, so don't be surprised if they can rally the troops against seemingly insurmountable odds.</p>",
                'de' => '<p>Widder ist das erste Zeichen des Tierkreises , und das ist ziemlich viel, wie diese unter diesem Zeichen geboren sie selbst sehen: zuerst. Widder sind die Führer der Packung, in erster Linie in Sachen in Gang zu bringen. Ob oder ob nicht alles wird getan ist eine ganz andere Frage, für ein Widder zu initiieren, anstatt abzuschließen vorzieht. Haben Sie ein Projekt benötigen einen Kick-Start? Rufen Sie einen Widder, mit allen Mitteln. Die Führung von Aries angezeigt ist sehr beeindruckend, so seien Sie nicht überrascht, wenn Sie die Truppen gegen scheinbar unüberwindliche Schwierigkeiten sammeln können.</p>'
            );
            $desciptions = array(
                'es' => "<p>El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez. No es probable que convencer al Ram para ablandar; gente de tesis son contundente y al grano. Junto con esas cualidades viene la pura fuerza de la naturaleza de Aries, una fuerza puede no lograr hecho un buen negocio. Gran parte de la unidad de Aries para competir y para ganar se debe a su calidad cardenal. Cardinales signos de amor para que funcione, y Aries ejemplifica esto incluso mejor que el Cáncer, Libra o Capricornio.El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez. El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez. No es probable que convencer al Ram para ablandar; gente de tesis son contundente y al grano. Junto con esas cualidades viene la pura fuerza de la naturaleza de Aries, una fuerza puede no lograr hecho un buen negocio. Gran parte de la unidad de Aries para competir y para ganar se debe a su calidad cardenal. Cardinales signos de amor para que funcione, y Aries ejemplifica esto incluso mejor que el Cáncer, Libra o Capricornio.El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez.</p>
                        <p>&nbsp;</p>
                        <p>El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez. No es probable que convencer al Ram para ablandar; gente de tesis son contundente y al grano. Junto con esas cualidades viene la pura fuerza de la naturaleza de Aries, una fuerza puede no lograr hecho un buen negocio. Gran parte de la unidad de Aries para competir y para ganar se debe a su calidad cardenal. Cardinales signos de amor para que funcione, y Aries ejemplifica esto incluso mejor que el Cáncer, Libra o Capricornio.El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez. El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez. No es probable que convencer al Ram para ablandar; gente de tesis son contundente y al grano. Junto con esas cualidades viene la pura fuerza de la naturaleza de Aries, una fuerza puede no lograr hecho un buen negocio. Gran parte de la unidad de Aries para competir y para ganar se debe a su calidad cardenal. Cardinales signos de amor para que funcione, y Aries ejemplifica esto incluso mejor que el Cáncer, Libra o Capricornio.El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez.</p>
                        <p>El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez. No es probable que convencer al Ram para ablandar; gente de tesis son contundente y al grano. Junto con esas cualidades viene la pura fuerza de la naturaleza de Aries, una fuerza puede no lograr hecho un buen negocio. Gran parte de la unidad de Aries para competir y para ganar se debe a su calidad cardenal. Cardinales signos de amor para que funcione, y Aries ejemplifica esto incluso mejor que el Cáncer, Libra o Capricornio.El símbolo de Aries es el carnero, y que a la vez Buenas y malas noticias. Impulsivo Aries podría verse tentado a embestir Sus ideas hasta en la sopa de todo el mundo, sin siquiera molestarse en preguntar si quieren saber. Es en momentos de tesis cuando termine tal vez desee símbolo de Aries era una criatura más tenue, más cordero que la RAM Tal vez.</p>",
                'en' => "<p>The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps. You're not likely to convince the Ram to soften up; these folks are blunt and to the point. Along with those qualities comes the sheer force of the Aries nature, a force that can actually accomplish a great deal. Much of Aries' drive to compete and to win comes from its Cardinal Quality. Cardinal Signs love to get things going, and Aries exemplifies this even better than Cancer, Libra or Capricorn.The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps.The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps. You're not likely to convince the Ram to soften up; these folks are blunt and to the point. Along with those qualities comes the sheer force of the Aries nature, a force that can actually accomplish a great deal. Much of Aries' drive to compete and to win comes from its Cardinal Quality. Cardinal Signs love to get things going, and Aries exemplifies this even better than Cancer, Libra or Capricorn.The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps.</p>
                        <p>The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps. You're not likely to convince the Ram to soften up; these folks are blunt and to the point. Along with those qualities comes the sheer force of the Aries nature, a force that can actually accomplish a great deal. Much of Aries' drive to compete and to win comes from its Cardinal Quality. Cardinal Signs love to get things going, and Aries exemplifies this even better than Cancer, Libra or Capricorn.The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps.The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps. You're not likely to convince the Ram to soften up; these folks are blunt and to the point. Along with those qualities comes the sheer force of the Aries nature, a force that can actually accomplish a great deal. Much of Aries' drive to compete and to win comes from its Cardinal Quality. Cardinal Signs love to get things going, and Aries exemplifies this even better than Cancer, Libra or Capricorn.The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps.</p>
                        <p>The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps. You're not likely to convince the Ram to soften up; these folks are blunt and to the point. Along with those qualities comes the sheer force of the Aries nature, a force that can actually accomplish a great deal. Much of Aries' drive to compete and to win comes from its Cardinal Quality. Cardinal Signs love to get things going, and Aries exemplifies this even better than Cancer, Libra or Capricorn.The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps.</p>
                        <p>&nbsp;</p>
                        <p>The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps. You're not likely to convince the Ram to soften up; these folks are blunt and to the point. Along with those qualities comes the sheer force of the Aries nature, a force that can actually accomplish a great deal. Much of Aries' drive to compete and to win comes from its Cardinal Quality. Cardinal Signs love to get things going, and Aries exemplifies this even better than Cancer, Libra or Capricorn.The symbol of Aries is the Ram, and that's both good and bad news. Impulsive Aries might be tempted to ram their ideas down everyone's throats without even bothering to ask if they want to know. It's these times when you may wish Aries' symbol were a more subdued creature, more lamb than ram perhaps.</p>",
                'de' => "<p>Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen. Du bist wahrscheinlich nicht die Ram davon zu überzeugen, zu erweichen; Diese Leute sind stumpf und auf den Punkt. Zusammen mit diesen Eigenschaften kommt die schiere Kraft der Widder Natur, eine Kraft whos Das kann sehr viel erreichen. Vieles von dem Auto Widder zu konkurrieren und zu gewinnen, kommt von seiner Cardinal Qualität. Kardinal Signs lieben es, Dinge in Gang zu bringen, und Widder exemplifiziert esta sogar besser als Krebs, Waage oder Steinbock.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen. Du bist wahrscheinlich nicht die Ram davon zu überzeugen, zu erweichen; Diese Leute sind stumpf und auf den Punkt. Zusammen mit diesen Eigenschaften kommt die schiere Kraft der Widder Natur, eine Kraft whos Das kann sehr viel erreichen. Vieles von dem Auto Widder zu konkurrieren und zu gewinnen, kommt von seiner Cardinal Qualität. Kardinal Signs lieben es, Dinge in Gang zu bringen, und Widder exemplifiziert esta sogar besser als Krebs, Waage oder Steinbock.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen.</p>
                        <p>&nbsp;</p>
                        <p>Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen. Du bist wahrscheinlich nicht die Ram davon zu überzeugen, zu erweichen; Diese Leute sind stumpf und auf den Punkt. Zusammen mit diesen Eigenschaften kommt die schiere Kraft der Widder Natur, eine Kraft whos Das kann sehr viel erreichen. Vieles von dem Auto Widder zu konkurrieren und zu gewinnen, kommt von seiner Cardinal Qualität. Kardinal Signs lieben es, Dinge in Gang zu bringen, und Widder exemplifiziert esta sogar besser als Krebs, Waage oder Steinbock.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen. Du bist wahrscheinlich nicht die Ram davon zu überzeugen, zu erweichen; Diese Leute sind stumpf und auf den Punkt. Zusammen mit diesen Eigenschaften kommt die schiere Kraft der Widder Natur, eine Kraft whos Das kann sehr viel erreichen. Vieles von dem Auto Widder zu konkurrieren und zu gewinnen, kommt von seiner Cardinal Qualität. Kardinal Signs lieben es, Dinge in Gang zu bringen, und Widder exemplifiziert esta sogar besser als Krebs, Waage oder Steinbock.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen. Du bist wahrscheinlich nicht die Ram davon zu überzeugen, zu erweichen; Diese Leute sind stumpf und auf den Punkt. Zusammen mit diesen Eigenschaften kommt die schiere Kraft der Widder Natur, eine Kraft whos Das kann sehr viel erreichen. Vieles von dem Auto Widder zu konkurrieren und zu gewinnen, kommt von seiner Cardinal Qualität. Kardinal Signs lieben es, Dinge in Gang zu bringen, und Widder exemplifiziert esta sogar besser als Krebs, Waage oder Steinbock.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen.</p>
                        <p>Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen. Du bist wahrscheinlich nicht die Ram davon zu überzeugen, zu erweichen; Diese Leute sind stumpf und auf den Punkt. Zusammen mit diesen Eigenschaften kommt die schiere Kraft der Widder Natur, eine Kraft whos Das kann sehr viel erreichen. Vieles von dem Auto Widder zu konkurrieren und zu gewinnen, kommt von seiner Cardinal Qualität. Kardinal Signs lieben es, Dinge in Gang zu bringen, und Widder exemplifiziert esta sogar besser als Krebs, Waage oder Steinbock.Das Symbol des Widders ist der Ram, und das ist gut und schlechte Nachrichten Beides. Impulsive Widder könnten ihre Gedanken auf jeden Kehlen, ohne auch nur die Mühe zu fragen, ob Sie wissen, Sie möchten zu rammen versucht sein. Es sind diese Zeiten, wenn Sie Widder wünschen Mai Symbol 'waren ein gedämpfter Kreatur, mehr als Lamm Vielleicht rammen.</p>"
            );
            
            //Create Categories
            foreach ($categories as $categoryName) {
                $category[$categoryName] = new Category();
                $category[$categoryName]->setName($categoryName);
                $category[$categoryName]->setDescription('Description category '.$categoryName);
                $category[$categoryName]->setMetaTitle('Meta titlte category '.$categoryName);
                $category[$categoryName]->setMetaDescription('Meta description category '.$categoryName);
                $this->getManager()->persist($category[$categoryName]);
            }
            //Create Tags
            foreach ($tags as $tagName) {
                $tag[$tagName] = new Tag();
                $tag[$tagName]->setName($tagName);
                $tag[$tagName]->setDescription('Description tag '.$tagName);
                $tag[$tagName]->setMetaTitle('Meta titlte tag '.$tagName);
                $tag[$tagName]->setMetaDescription('Meta description tag '.$tagName);
                $this->getManager()->persist($tag[$tagName]);
            }
            //Create Post
            $post = new Post();
            $postTrans = array();
            foreach ($locales as $locale) {
                $postTrans[$locale] = new PostTranslation();
                $postTrans[$locale]->setLocale($locale);
                $postTrans[$locale]->setTitle($titles[$locale]);
                $postTrans[$locale]->setShortDescription($shortDesciptions[$locale]);
                $postTrans[$locale]->setDescription($desciptions[$locale]);
                $postTrans[$locale]->setMetaTitle($titles[$locale]);
                $postTrans[$locale]->setMetaDescription($shortDesciptions[$locale]);
                $postTrans[$locale]->setTranslatable($post);
                $post->addTranslation($postTrans[$locale]);
                $this->getManager()->persist($postTrans[$locale]);
            }
            $post->addCategory($category['Signs']);
            $post->addTag($tag['Aries']);
            $post->addTag($tag['Fire']);
            $post->setActor($actor);
            $post->setHighlighted(true);
            $this->getManager()->persist($post);
           
            $post2 = new Post();
            foreach ($locales as $locale) {
                $postTrans[$locale] = new PostTranslation();
                $postTrans[$locale]->setLocale($locale);
                $postTrans[$locale]->setTitle($titles[$locale]);
                $postTrans[$locale]->setShortDescription($shortDesciptions[$locale]);
                $postTrans[$locale]->setDescription($desciptions[$locale]);
                $postTrans[$locale]->setMetaTitle($titles[$locale]);
                $postTrans[$locale]->setMetaDescription($shortDesciptions[$locale]);
                $postTrans[$locale]->setTranslatable($post);
                $post2->addTranslation($postTrans[$locale]);
                $this->getManager()->persist($postTrans[$locale]);
            }
            $post2->addCategory($category['Signs']);
            $post2->addCategory($category['Compatibility']);
            $post2->addTag($tag['Earth']);
            $post2->addTag($tag['Fire']);
            $post2->addTag($tag['Moon']);
            $post2->setActor($actor);
            $post2->setHighlighted(true);
            $this->getManager()->persist($post2);
            
            $post3 = new Post();
            foreach ($locales as $locale) {
                $postTrans[$locale] = new PostTranslation();
                $postTrans[$locale]->setLocale($locale);
                $postTrans[$locale]->setTitle($titles[$locale]);
                $postTrans[$locale]->setShortDescription($shortDesciptions[$locale]);
                $postTrans[$locale]->setDescription($desciptions[$locale]);
                $postTrans[$locale]->setMetaTitle($titles[$locale]);
                $postTrans[$locale]->setMetaDescription($shortDesciptions[$locale]);
                $postTrans[$locale]->setTranslatable($post);
                $post3->addTranslation($postTrans[$locale]);
                $this->getManager()->persist($postTrans[$locale]);
            }
            $post3->addCategory($category['Signs']);
            $post3->addCategory($category['Planets']);
            $post3->addTag($tag['Earth']);
            $post3->addTag($tag['Fire']);
            $post3->addTag($tag['Moon']);
            $post3->setActor($actor);
            $post3->setHighlighted(true);
            $this->getManager()->persist($post3);
             
            $this->getManager()->getFilters()->disable('oneLocale');
            $this->getManager()->flush();
            
        }
    }
    
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
