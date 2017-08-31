To create 'alerter.phar' use the following command:    php -d phar.readonly=0 box.phar build -v

It will create it in the dist directory (if exists - the path can be modified from box.json).

To run the alerter use the command:

php alerter.phar start -c config/config.yml


config.yml should contain the following:

* config - settings configuration
 - interval (in minutes - the interval between each check)
 - threshold (the number of messages max allowed in queue before sending alert)
 - time (the number of checks before sending alert)

* mail - email connection settings:
 - mailer_transport
 - mailer_host
 - mailer_user
 - mailer_password
 - mailer_port
 - mail_from
 - mail_to

* monitor - queues that are to be monitored
 - name of queue group
   - type (type of connection)
   - details 
     - connection ( name of connection to retrieve details)
     - name (name of queue)

* common - settings for connection to rabbit
 - name of connection
   - hostname
   - port
   - username
   - password
   - vhost


