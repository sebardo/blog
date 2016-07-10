# blog
Blog bundle for sandbox



Command line to install blog and dependencies:

php composer.phar require sebardo/core:dev-master sebardo/admin:dev-master sebardo/blog:dev-master

Add required to composer.json

        "doctrine/doctrine-fixtures-bundle": "dev-master",
        "symfony/assetic-bundle": "^2.8",
        "twig/extensions": "~1.0",
        "gedmo/doctrine-extensions":  "dev-master",
        "google/apiclient": "@beta",
        "hwi/oauth-bundle": "0.4.*@dev",
        "ensepar/html2pdf-bundle" : "~2.0",
        
        "sebardo/core": "dev-master",
        "sebardo/admin": "dev-master",
        "sebardo/blog": "dev-master"
        
Add paramters to parameter.yml

    node_path: /usr/bin/node
    node_modules_path: /usr/lib/node_modules
    core:
        name: Kundalini Woman
        extended_layout: 'FrontBundle:Base:layout.html.twig'
        extended_layout_admin: 'AdminBundle:Base:layout.html.twig'
        upload_directory: uploads
        server_base_url: 'http://kundaliniwoman.dev'
        fixtures_test: false
        admin_email: admin@admin.com
        company:
            id: XXX123123
            name: Kundalini Woman
            address: 'Weimheimer str. 12'
            postal_code: '14199'
            city: Berlin
            country: Alemania
            telephone: '0157 557 58150'
            email: sj.ehlemann@gmail.com
            website_url: www.kundaliniwoman.com
            instagram: 'kundaliniwoman'
    admin:
        - google_application_name: Analitycs
        - google_oauth2_client_id: 459960642348-nqhsk0rr1e41gv3kbb519g1nlk6rq78a.apps.googleusercontent.com
        - google_oauth2_client_secret: KScqc1jDkZHUBaanmMGV8QpB
        - google_oauth2_redirect_uri: 'http://optisoop2.dev/admin/analitycs'
        - google_developer_key: AIzaSyCda_bsJ-kEa1M1DJenwKfUfyLVlVKuC6I
        - google_site_name: Kundalini Woman

Add class to AppKernel.php

        new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        new Symfony\Bundle\AsseticBundle\AsseticBundle(),
        new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
        new Ensepar\Html2pdfBundle\EnseparHtml2pdfBundle(),
        
        new CoreBundle\CoreBundle(),
        new AdminBundle\AdminBundle(),
        new BlogBundle\BlogBundle(),
        
Add routing.yml to route file

        app:
            resource: "@CoreBundle/Resources/config/routing.yml"
            prefix:   /
