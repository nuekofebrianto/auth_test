<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## Auth Test

Kebutuhan:

- Docker(https://hub.docker.com).
- Postman(https://www.postman.com).

Instalasi:

- Clone projek ini ke lokal kamu (git clone https://gitlab.com/nuekofebrianto/auth_test.git).
- rename file .env.example menjadi .env dan setting data berikut
	- DB_DATABASE
	- DB_USERNAME
	- DB_PASSWORD

- pastikan 'docker daemon' sudah berjalan.
- dengan command line , masuk ke dalam projek path.
- ketikan
	- docker-compose up -d --build (tunggu sampai selesai)
	- docker-compose exec app composer update
	- docker-compose exec app php artisan key:generate
	- docker-compose exec app php artisan jwt:secret
	- ketik y , enter
	- docker-compose exec app php artisan migrate

Testing:
- masih dalam command line ,ketikan
	- docker-compose exec app vendor/bin/phpunit


Cara Menggunakan:
- buka postman
- pilih file , import
- pada tab file , klik upload file
- pilih file "auth_test.postman_collection.json" yang ada di dalam projek ini
- dalam collection-nya terdapat 3 collection
	- 1.register
		masukan parameter username dan role pada body , klik send.
		copy value password pada respon yang muncul.
	- 2.login
		masukan parameter username dan password pada body , klik send.
		copy value token pada respon yang muncul.
	- 3.validate_token
		masukan parameter Authorization pada Headers
		masukan valuenya , bearer (paste token yang sebelumnya di copy) , klik send.

