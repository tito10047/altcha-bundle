init:
	mkdir -p tools/php-cs-fixer
	composer require --dev --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer

format:
	PHP_CS_FIXER_IGNORE_ENV=1 tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src