# Synology Put.io

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D5.6-blue.svg)](https://www.php.net/)
[![Synology](https://img.shields.io/badge/Platform-Synology%20DSM-orange.svg)](https://www.synology.com/)

A file hosting module for Synology Download Station that enables downloading files from [Put.io](https://put.io) premium accounts directly to your Synology NAS.

## Features

- ✅ Seamless integration with Synology Download Station
- ✅ Support for Put.io premium accounts
- ✅ Direct download from Put.io to your NAS
- ✅ Support for various Put.io URL formats (files, MP4 conversions, zipstream)
- ✅ Cross-platform compatible (uses system temp directory)
- ✅ Easy configuration through Download Station interface

## Requirements

- **Synology NAS** with DSM 6.0 or higher
- **Download Station** package installed
- **Put.io Premium Account**
- **PHP** 5.6 or higher (pre-installed on most Synology NAS)

## Installation

### Method 1: Download Pre-built Package

1. Download the latest `putio.host` file from the [releases page](https://github.com/barisariburnu/synology-putio/releases)

### Method 2: Build from Source

1. Clone this repository:
   ```bash
   git clone https://github.com/barisariburnu/synology-putio.git
   cd synology-putio
   ```

2. Create the `.host` package:
   ```bash
   tar -czf putio.host INFO putio.php LICENSE
   ```

### Configure in Synology

1. Login to your Synology NAS DSM interface (usually `https://YOUR_SYNOLOGY_IP:5001`)
2. Open the **Download Station** package
3. Go to **Settings** (gear icon)
4. Select **File Hosting** from the left sidebar
5. Click **Add** button
6. Browse and select the `putio.host` file
7. Once added, click **Edit** next to "Putio"
8. Enter your Put.io credentials:
   - **Username**: Your Put.io email or username
   - **Password**: Your Put.io password
9. Click **OK** to save

## Usage

1. Copy any Put.io file URL (e.g., `https://put.io/files/123456789`)
2. In Download Station, click the **+** (Add) button
3. Paste the Put.io URL
4. Download Station will automatically recognize it as a Put.io link
5. The file will be downloaded directly to your NAS

### Supported URL Formats

The module supports the following Put.io URL formats:

- **Standard files**: `https://put.io/files/[FILE_ID]`
- **V2 API format**: `https://put.io/v2/files/[FILE_ID]`
- **MP4 conversions**: `https://put.io/files/[FILE_ID]/mp4`
- **Zip streams**: `https://put.io/zipstream/[STREAM_ID]?params`

## Development

### Project Structure

```
synology-putio/
├── putio.php          # Main module logic
├── INFO               # Module metadata
├── LICENSE            # MIT License
├── README.md          # This file
├── CONTRIBUTING.md    # Contribution guidelines
└── CHANGELOG.md       # Version history
```

### Configuration

The module automatically uses the system's temporary directory for cookie storage, making it cross-platform compatible:

```php
sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'putio.cookie'
```

## Troubleshooting

### Login Fails

- Verify your Put.io credentials are correct
- Ensure you have an active Put.io premium account
- Check that your NAS has internet connectivity
- Try removing and re-adding the file hosting module

### Downloads Don't Start

- Verify the Put.io URL format is correct
- Check that the file exists in your Put.io account
- Ensure the file isn't expired or deleted
- Check Download Station logs for detailed error messages

### Cookie Issues

The module stores authentication cookies in the system temp directory. If you encounter persistent login issues:

1. SSH into your Synology NAS
2. Remove the cookie file:
   ```bash
   rm -f /tmp/putio.cookie
   ```
3. Try logging in again through Download Station

## Contributing

Contributions are welcome! Please read our [Contributing Guidelines](CONTRIBUTING.md) before submitting pull requests.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [Put.io](https://put.io) for their excellent cloud storage service
- Synology for their NAS platform and Download Station API
- All contributors who have helped improve this project

## Author

**Barış Arıburnu**  
GitHub: [@barisariburnu](https://github.com/barisariburnu)

## Support

- **Issues**: [GitHub Issues](https://github.com/barisariburnu/synology-putio/issues)
- **Discussions**: [GitHub Discussions](https://github.com/barisariburnu/synology-putio/discussions)

---

**Note**: This is an unofficial module and is not affiliated with, endorsed, or supported by Put.io or Synology.
