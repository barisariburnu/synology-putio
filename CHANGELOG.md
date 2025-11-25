# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- Refactored cookie path to use system temp directory for cross-platform compatibility
- Improved code documentation with PHPDoc comments
- Enhanced error handling and logging

### Added
- Comprehensive README with installation and usage instructions
- CONTRIBUTING guidelines for open source collaboration
- This CHANGELOG file to track version history

### Removed
- Debug code and commented-out development artifacts

## [1.3] - 2015-12-20

### Added
- Support for Put.io v2 API endpoints
- MP4 conversion URL support
- Zipstream URL support
- Multiple URL format detection

### Changed
- Updated authentication flow to use login_token2
- Improved URL parsing logic

### Fixed
- Cookie authentication issues
- Download token generation

## [1.2] - 2015-10-15

### Added
- Premium account verification
- Access token retrieval from Put.io API

### Fixed
- Login failure handling
- HTTP 403 error handling

## [1.1] - 2015-09-01

### Added
- Initial Cookie-based authentication
- Basic Put.io file download support

### Fixed
- Connection timeout issues
- SSL verification problems

## [1.0] - 2015-08-15

### Added
- Initial release
- Basic Synology Download Station integration
- Put.io authentication support
- Simple file download functionality

---

## Release Notes

### Version Numbering

This project uses [Semantic Versioning](https://semver.org/):
- **MAJOR** version: Incompatible API changes
- **MINOR** version: New functionality (backwards-compatible)
- **PATCH** version: Bug fixes (backwards-compatible)

### Upgrade Instructions

To upgrade to a new version:

1. Download the new `putio.host` file
2. In Download Station > Settings > File Hosting
3. Select "Putio" and click **Delete**
4. Click **Add** and select the new `putio.host` file
5. Click **Edit** and re-enter your Put.io credentials

### Breaking Changes

None currently. All versions maintain backwards compatibility with Synology DSM 6.0+.

---

[Unreleased]: https://github.com/barisariburnu/synology-putio/compare/v1.3...HEAD
[1.3]: https://github.com/barisariburnu/synology-putio/releases/tag/v1.3
[1.2]: https://github.com/barisariburnu/synology-putio/releases/tag/v1.2
[1.1]: https://github.com/barisariburnu/synology-putio/releases/tag/v1.1
[1.0]: https://github.com/barisariburnu/synology-putio/releases/tag/v1.0
