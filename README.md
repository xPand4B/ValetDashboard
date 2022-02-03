# Valet-Dashboard
A simple dashboard for Laravel Valet.

![ValetDashboard Screenshot](assets/valet_dashboard_110.png?raw=true "ValetDashboard Screenshot")

- **License**: [MIT License](LICENSE.md)
- **GitHub Repository**: <https://github.com/xPand4B/ValetDashboard>
- **Issue Tracker**: <https://github.com/xPand4B/ValetDashboard/issues>

## How to install
### Option A - Set as default _(Recommended)_
1. Clone or download the repo
2. Go to `~/.config/valet/config.json` and set the key `default` to the directory of the project. Read more in [their documentation here](https://laravel.com/docs/8.x/valet#serving-a-default-site).

### Option B - Link Project
1. Clone or download the repo
2. Run `valet link` inside the project directory
3. Run `valet open` to get the dashboard _(default: http://valetdashboard.test/)_