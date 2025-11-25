# Contributing to Synology Put.io

First off, thank you for considering contributing to Synology Put.io! It's people like you that make this project better for everyone.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
  - [Reporting Bugs](#reporting-bugs)
  - [Suggesting Enhancements](#suggesting-enhancements)
  - [Pull Requests](#pull-requests)
- [Development Setup](#development-setup)
- [Style Guidelines](#style-guidelines)
  - [Git Commit Messages](#git-commit-messages)
  - [PHP Style Guide](#php-style-guide)
- [Testing](#testing)

## Code of Conduct

This project and everyone participating in it is governed by respect and professionalism. By participating, you are expected to uphold this standard. Please report unacceptable behavior to [barisariburnu](https://github.com/barisariburnu).

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the [existing issues](https://github.com/barisariburnu/synology-putio/issues) to avoid duplicates.

When you create a bug report, please include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples** to demonstrate the steps
- **Describe the behavior you observed** and what you expected
- **Include screenshots** if applicable
- **Environment details**:
  - Synology DSM version
  - Download Station version
  - PHP version (from DSM info)
  - Put.io URL format that failed

**Example bug report:**

```markdown
**Title**: Download fails for MP4 conversion URLs

**Description**: 
When trying to download a Put.io file with MP4 conversion 
(e.g., https://put.io/files/123456/mp4), the download fails 
with "File not found" error.

**Steps to Reproduce**:
1. Add Put.io file hosting module
2. Configure with valid credentials
3. Try to download URL: https://put.io/files/123456/mp4

**Expected**: File downloads successfully
**Actual**: Error message "File not found"

**Environment**:
- DSM Version: 7.1
- Download Station: 3.8.16-3566
- PHP Version: 7.4
```

### Suggesting Enhancements

Enhancement suggestions are tracked as [GitHub issues](https://github.com/barisariburnu/synology-putio/issues).

When creating an enhancement suggestion, please include:

- **Use a clear and descriptive title**
- **Provide a detailed description** of the suggested enhancement
- **Explain why this enhancement would be useful** to most users
- **List any alternative solutions** you've considered

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Make your changes** following the style guidelines
3. **Test your changes** on a Synology NAS if possible
4. **Update documentation** if needed
5. **Write a clear commit message**
6. **Submit a pull request**

## Development Setup

### Prerequisites

- Git
- Access to a Synology NAS for testing (recommended)
- PHP knowledge
- Text editor or IDE

### Setup Steps

1. **Fork and clone the repository**:
   ```bash
   git clone https://github.com/YOUR_USERNAME/synology-putio.git
   cd synology-putio
   ```

2. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes** to the code

4. **Build the package**:
   ```bash
   tar -czf putio.host INFO putio.php LICENSE
   ```

5. **Test on Synology NAS**:
   - Upload `putio.host` to Download Station
   - Test with various Put.io URL formats
   - Verify login and download functionality

## Style Guidelines

### Git Commit Messages

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters or less
- Reference issues and pull requests after the first line

**Examples**:
```
Add support for Put.io v3 API endpoints

- Update authentication flow
- Add new URL parsing logic
- Update tests

Fixes #123
```

### PHP Style Guide

- **Indentation**: Use tabs (matching Synology's default)
- **Braces**: Opening brace on same line
- **Naming**:
  - Classes: `PascalCase`
  - Methods: `PascalCase` (following Synology conventions)
  - Variables: `camelCase`
  - Private members: prefix with `$`
- **Comments**: Use PHPDoc style for functions

**Example**:

```php
/**
 * Get download information from Put.io URL
 * 
 * @param string $url Put.io URL
 * @return array Download information
 */
public function GetDownloadInfo() {
	if ($this->ValidateUrl()) {
		// Process URL
		return $result;
	}
}
```

### Code Organization

- Keep functions focused and single-purpose
- Add comments for complex logic
- Use meaningful variable names
- Avoid hardcoded values (use constants or config)

## Testing

While automated testing is limited for Synology modules, please test manually:

### Test Checklist

- [ ] Login with valid Put.io credentials works
- [ ] Login with invalid credentials fails gracefully
- [ ] Standard file URLs download correctly
- [ ] MP4 conversion URLs work
- [ ] Zipstream URLs work
- [ ] Error messages are clear and helpful
- [ ] Cookie file is created in correct location
- [ ] Module works after DSM reboot

### Test URLs

Test with various Put.io URL formats:

```
https://put.io/files/123456789
https://put.io/v2/files/123456789
https://put.io/files/123456789/mp4
https://put.io/zipstream/xxx?params
```

## Questions?

- Open an [issue](https://github.com/barisariburnu/synology-putio/issues)
- Start a [discussion](https://github.com/barisariburnu/synology-putio/discussions)

## Recognition

Contributors will be recognized in the project README and release notes. Thank you for making this project better!

---

**Thank you for contributing to Synology Put.io!** 🎉
