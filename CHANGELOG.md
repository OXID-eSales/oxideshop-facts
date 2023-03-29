# Change Log for OXID eShop facts

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/ ).
and this project adheres to [Semantic Versioning](http://semver.org/ ).

## [v4.1.0] - 2023-04-19

### Changed
- License update

### Removed
- Dependency to webmozart/path-util


## [4.0.0] - 2022-10-06

### Removed
- Composer v1 support

## [v3.0.0] - 2021-06-10

### Changed

- Update symfony components to version 5

## [v2.4.1] - Unreleased

### Fixed
- Passing null to string parameter in `Config\ConfigFile`

## [v2.4.0] - 2021-04-12

### Deprecated

`OxidEsales\Facts::getMigrationPaths` method

## [v2.3.2] - 2020-03-19

### Added

- Add `getCommunityEditionRootPath` to get community edition root path

### Fixed

- Fixed naming of required components [PR-3](https://github.com/OXID-eSales/oxideshop-facts/pull/3)

## [v2.3.1] - 2018-12-03

### Changed

- Exclude tests from dist-packages [PR-2](https://github.com/OXID-eSales/oxideshop-facts/pull/2)

## [v2.3.0] - 2018-05-24

### Added

- `Facts::getShopUrl()`

[v2.3.0]: https://github.com/OXID-eSales/oxideshop-facts/compare/v2.2.0...v2.3.0

## [v2.2.0] - 2018-03-29

### Added

- `Facts::getDatabasePort()`

[v4.1.0]: https://github.com/OXID-eSales/oxideshop-facts/compare/v4.0.0...v4.1.0
[v4.0.0]: https://github.com/OXID-eSales/oxideshop-facts/compare/v3.0.0...v4.0.0
[v3.0.0]: https://github.com/OXID-eSales/oxideshop-facts/compare/v2.4.0...v3.0.0
[v2.4.0]: https://github.com/OXID-eSales/oxideshop-facts/compare/v2.3.2...v2.4.0
[v2.3.2]: https://github.com/OXID-eSales/oxideshop-facts/compare/v2.3.1...v2.3.2
[v2.3.1]: https://github.com/OXID-eSales/oxideshop-facts/compare/v2.3.0...v2.3.1
[v2.3.0]: https://github.com/OXID-eSales/oxideshop-facts/compare/v2.2.0...v2.3.0
[v2.2.0]: https://github.com/OXID-eSales/oxideshop-facts/compare/v2.1.0...v2.2.0
