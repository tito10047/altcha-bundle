init:
	mkdir -p tools/php-cs-fixer
	composer require --dev --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer

format:
	tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src