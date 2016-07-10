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

Remove this lines in config.yml

        - { resource: security.yml }
        - { resource: services.yml }
        
And add this lines or edit this lines in config.yml
        
        - { resource: "@CoreBundle/Resources/config/security.yml" }
        - { resource: "@CoreBundle/Resources/config/services.yml" }
        - { resource: "@AdminBundle/Resources/config/services.yml" }
        - { resource: "@BlogBundle/Resources/config/services.yml" }

        parameters:
            locale: es
            
        # Twig Configuration
        twig:
            debug:            "%kernel.debug%"
            strict_variables: "%kernel.debug%"
            globals:
                core: %core%

        # Assetic Configuration
        assetic:
            debug:          "%kernel.debug%"
            use_controller: '%kernel.debug%'
            bundles:
                [ CoreBundle, AdminBundle, BlogBundle, FrontBundle ]
            node: "%node_path%"
            filters:
                cssrewrite:
                    apply_to: "\.css$"
                less:
                    node: "%node_path%"
                    node_paths: ["%node_modules_path%"]
                    apply_to: "\.less$"
                  
        # OAuth login social networks  
        hwi_oauth:
             #name of the firewall in which this bundle is active, this setting MUST be set
        
            firewall_name: secured_area
            target_path_parameter: /
            resource_owners:
                twitter:
                    type:                twitter
                    client_id:           lV0OGkdpom7fu0umpyOYl69v4
                    client_secret:       i0JA4XNVvqGLED88X031208nJzydR07ek3zNOPjqWtiaoEyTsU
                google:
                    type:                google
                    client_id:           295710704391-kuvgr89k3empant281ilbk0aescnhiee.apps.googleusercontent.com
                    client_secret:       Vvo_sIbWW7mdi-vLh6tpLkMa
                    scope:               "email profile"
                    options:
                        access_type:     offline
                        approval_prompt: force
                        display:         popup
                        login_hint:      sub
                facebook:
                    type:                facebook
                    client_id:           502605306534870
                    client_secret:       8e85bc722eff0f6485ebf08abad30f5b
                    scope:               "email"
                    options:
                        display: popup 
                        
        # Pdf exportation
        ensepar_html2pdf:
            orientation: P
            format: A4
            lang: en
            unicode: true
            encoding: UTF-8
            margin: [10,15,10,15]
            
        # Doctrine DQL funtions added
        doctrine:
            ...
            orm:
                auto_generate_proxy_classes: "%kernel.debug%"
                naming_strategy: doctrine.orm.naming_strategy.underscore
                auto_mapping: true
                dql:
                    numeric_functions:
                        DISTANCE: CoreBundle\Functions\DistanceFunction
                    string_functions:
                        GroupConcat: CoreBundle\Functions\GroupConcatFunction
