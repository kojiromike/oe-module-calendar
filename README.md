# OpenCoreEMR Calendar Module for OpenEMR

## Overview

This OpenEMR module connects to the OpenCoreEMR calendar application to provide a modern and robust calendar and event system. It enhances the default calendar capabilities in OpenEMR with additional features and improved user experience.

## Features

- Integration with OpenCoreEMR Calendar application
- Modern user interface for appointment management
- Enhanced event scheduling and management
- Real-time calendar updates
- Improved notification system
- Multi-provider calendar views
- Resource scheduling capabilities
- Patient appointment self-service options

## Requirements

- OpenEMR version 7.0.1 or higher
- PHP 8.1 or higher
- Active OpenCoreEMR Calendar subscription

## Installation

### Via Composer (Recommended)

```bash
composer require opencoreemr/oe-module-calendar
```

### Manual Installation

1. Download the latest release from the GitHub repository
2. Extract the contents into the `interface/modules/custom_modules/` directory of your OpenEMR installation
3. Navigate to Modules -> Manage Modules in your OpenEMR admin panel
4. Enable the "OpenCoreEMR Calendar" module

## Configuration

After installation, configure the module by navigating to:

1. Modules -> Manage Modules
2. Click "Configure" next to the OpenCoreEMR Calendar module
3. Enter your OpenCoreEMR Calendar API credentials
4. Configure calendar settings according to your needs
5. Save the configuration

## Usage

Once installed and configured, the enhanced calendar can be accessed through:

- The Calendar menu in the main navigation
- The patient dashboard appointment section
- The provider schedule view

## Development

### Prerequisites

- PHP 8.1 or higher
- Composer

### Setup Development Environment

```bash
# Clone the repository
git clone https://github.com/your-organization/oe-module-calendar.git

# Install dependencies
cd oe-module-calendar
composer install
```

### Running Tests

```bash
composer test
```

### Code Style

This project follows PSR-12 coding standards. To check and fix code style:

```bash
composer fix-style
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## License

This project is licensed under the GNU General Public License v3.0.

## Authors

- Michael A. Smith - michael@smith-li.com

## Support

For support and questions, please create an issue in the GitHub repository or contact support@opencoreemr.com.