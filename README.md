# Hub v 0.6

## Note: Still in development, not recommended for production use!

An open graph inspired API and website for Hyperboria. Using a JSON based specification called nodeinfo.json, hub has pioneered a standard for self-hosted structured data exchange on the cjdns powered Hyperboria network. 

##### API

Hub features a RESTful API, with pretty printed JSON.

##### Nodes

Hub can monitor the network using any connected cjdns node's admin API, creating a node directory with various node info.

##### People

A directory of node operators, comprised of data collected from self-hosted nodeinfo.json as well as information added from the website. Using a concept similiar to KeyBase, hub aims to provide an authoritive people graph for Hyperboria collected from self-hosted/public sources.

##### Services

A directory of verified active services.

## Dependencies

 - PHP 5.5+
 - Cjdns + Cjdns Admin API
 - Composer
 - Laravel

## Installation

Development Installation

1. ```git clone github.com/dansup/hub```
2. ```cp .env.example .env``
3. Add database credentials, cjdns ip and base url to .env
4. ```composer install``
5. php artisan migrate

## Contributing

Hub follows the PSR-2 coding standard and the PSR-4 autoloading standard.

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D


## License

GPLv3, available [here](https://github.com/dansup/hub/blob/master/LICENCE)