# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [2.0.11] - 2025-11-14
- Fallback to main request when current request doesn't have a value

## [2.0.10] - 2025-11-13
- sentinel: use the RequestInit into the custom fetch

## [2.0.9] - 2025-11-08
- Add support for symfony 8.0

## [2.0.7] - 2025-11-08
- Add 'max_number' and 'expires' options to config

## [2.0.6] - 2025-10-26
- Add 'content' option to Overlay UI mode

## [2.0.4] - 2025-10-24
- Implement Altcha Overlay UI mode

## [2.0.3] - 2025-10-22
- fix dependencies

## [2.0.0] - 2025-09-24
- BC break: include_script must be set to true by default on non webpack or asset mapper projects
- BC break: renamed namespace from Huluti\HulutiAltchaBundle to Tito10047\AltchaBundle 
- BC break: renamed config key from huluti_altcha to altcha 
- BC break: renamed path to route file in config/routes/huluti_altcha.yaml '@HulutiAltchaBundle/config/routes.yml' to '@AltchaBundle/config/routes.yml'
- Introduce option include_script to disable script tag
- Abandoned use_asset_mapper and use_webpack options which is now replaced by include_script:false
- Added functional tests for Webpack, AssetMapper or Twig rendering

## [1.7.5] - 2025-09-10
- Allow null value for i18npath to be more flexible in configuration

## [1.7.2] - 2025-08-21
- Fix ln18 without asset mapper.

## [1.7.1] - 2025-07-02
- sentinel: fallback using the local instance instead of completely disabling verification

## [1.7.0] - 2025-07-01
- bugfix: widget block is not responsible of displaying form errors
- Support Altcha Sentinel

## [1.6.3] - 2025-06-10
- Import the i18n version of altcha

## [1.6.2] - 2025-06-10
- Really fix altcha import

## [1.6.1] - 2025-06-10
- Fix altcha import

## [1.6.0] - 2025-06-10
- Switch to native translations introduced in Altcha v2

## [1.5.0] - 2025-06-03
- Bump Altcha js lib to v2

## [1.4.0] - 2025-06-03
- Drop script tag if Asset Mapper is installed

## [1.3.0] - 2025-05-19
- Bump Altcha PHP dependency to 1.x
- Drop support for PHP 8.1

## [1.2.0] - 2025-05-11
- No label by default
- Better documentation
- Better tests

## [1.1.0] - 2025-03-20
- Add options to AltchaType
- Add more tests
- Add Slovak translations
- Fix two deprecations

## [1.0.1] - 2025-02-17
- Add French translation

## [1.0.0] - 2025-02-17
- Support Asset Mapper
- Support translations
- Support more options
- Add tests

## [0.2] - 2024-12-13
- Don't refresh captcha under live components

## [0.1] - 2024-11-24
- First release