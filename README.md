# ns-cloner-api
Adds an endpoint to the Wordpress REST API to allow NS Cloner to copy sites in a multisite installation.

# Notes

It is recommended to use this plugin with

- JWT Authentication for WP REST API (https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/)
- NS Cloner (https://de.wordpress.org/plugins/ns-cloner-site-copier/)
- The user you use must have the 'export' capability

# Endpoint

- POST http://HOSTNAME/wp-json/ns-cloner/v1/copy
    - Headers:
        - Content-Type: application/json
    - Body:
        - source_id: ID of the blog you wanna copy.
        - target_name: The Name of the new blog (e.g. NAME.HOSTNAME.COM). Be aware that there is no check yet if the name already exists.
        - target_title: The Title of the new blog