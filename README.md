# Orinoco Framework

A lightweight PHP framework.

## Controller

### Basic

Here is an example of a basic controller class:

    <?php

    class fooController
    {
            public function __construct()
            {
                    // This method will be executed upon (this) class instantiation
                    // For example, you can use this method to initialize a private/public variable
            }

            // action index
            public function index()
            {
                    // Executed on request URI /foo
            }

            // action bar
            public function bar()
            {
                    // Executed on request URI /foo/bar
            }
    }

Though the above controller class will work just fine but in real world, you need to add logic to your controller and action methods. So here is an example of a simple `log` controller with basic logic:


    <?php

    // Use framework's built-in View class
    use Orinoco\Framework\View as View;

    // Use Monolog (vendor class, installed via Composer)
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    class logController
    {
            // View object instance will be injected automatically
            // So you don't need to instantiate a new View object
            public function index(View $view)
            {
                    // Create a log channel
                    $log = new Logger('name');
                    $log->pushHandler(new StreamHandler('/tmp/monolog.txt', Logger::WARNING));

                    // Add records to the log channel
                    $log->addWarning('Foo');
                    $log->addError('Bar');

                    // Assuming everything went OK, output a JSON response
                    return $view->renderJSON(array(
                        'ok' => true,
                        'message' => 'Log written successfully.'
                    ));
            }
    }

## Requirement

Orinoco Framework requires PHP >= 5.4.0 version.

## Installation

1. Clone framework source:

        $ mkdir myapp
        $ git clone git://github.com/rawswift/orinoco-framework-php.git myapp/

2. Setup (Nginx) virtual host: (remember to point `root` to `/path/to/myapp/app/www`)

        server {
                listen      80;
                server_name myapp.com;
                access_log  /var/log/nginx/myapp.com.access.log;
                error_log   /var/log/nginx/myapp.com.error.log;
                rewrite_log on;
                root        /path/to/myapp/app/www;
                index       index.php;
                if (!-e $request_filename) {
                        rewrite ^/(.+)$ /index.php last;
                        break;
                }
                location ~ \.php$ {
                        fastcgi_pass   127.0.0.1:9000;
                        fastcgi_index  index.php;
                        fastcgi_intercept_errors on; # to support 404s for PHP files not found
                        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
                        client_max_body_size 20M;
                        include        fastcgi_params;
                }
                location ~* \favicon.ico$ {
                        access_log off;
                        expires 1d;
                        add_header Cache-Control public;
                }
                location ~ ^/(img|cjs|ccss)/ {
                        access_log off;
                        expires 7d;
                        add_header Cache-Control public;
                }
                 location ~ /(\.ht|\.git|\.svn) {
                        deny  all;
                }
        }

## License

Licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php)