# Deployment Guide - Vier op een Rij

This document describes how to deploy the Connect Four application to production.

## Prerequisites

- CapRover server instance
- GitHub repository with the application code
- Database (MariaDB/MySQL) configured on CapRover

## CapRover Deployment

### 1. Create App on CapRover

```bash
# Log in to your CapRover server
caprover login

# Create a new app
caprover create vier-op-een-rij
```

### 2. Configure Environment Variables

In the CapRover dashboard, configure the following environment variables for your app:

```env
APP_NAME="Vier op een Rij"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=srv-captain--mariadb
DB_PORT=3306
DB_DATABASE=vier_op_een_rij
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Generate a key using: php artisan key:generate --show
APP_KEY=base64:your-generated-key-here
```

### 3. Set Up GitHub Actions

Add the following secrets to your GitHub repository (Settings > Secrets and variables > Actions):

- `CAPROVER_SERVER`: Your CapRover server URL (e.g., `https://captain.your-domain.com`)
- `CAPROVER_APP`: Your app name (e.g., `vier-op-een-rij`)
- `CAPROVER_TOKEN`: Your CapRover app token (get from CapRover dashboard)

### 4. Deploy

Push to the `main` branch to trigger automatic deployment:

```bash
git push origin main
```

Or deploy manually using CapRover CLI:

```bash
caprover deploy
```

## Manual Docker Build

If you want to build and test the Docker image locally:

```bash
# Build the image
docker build -t vier-op-een-rij .

# Run the container
docker run -d \
  -p 8080:80 \
  -e APP_KEY=base64:your-key-here \
  -e DB_HOST=your-db-host \
  -e DB_DATABASE=vier_op_een_rij \
  -e DB_USERNAME=your_username \
  -e DB_PASSWORD=your_password \
  --name vier-op-een-rij \
  vier-op-een-rij

# Check logs
docker logs -f vier-op-een-rij
```

## Database Setup

The application automatically runs migrations on startup. Make sure your database is created:

```sql
CREATE DATABASE IF NOT EXISTS vier_op_een_rij;
GRANT ALL PRIVILEGES ON vier_op_een_rij.* TO 'your_username'@'%';
FLUSH PRIVILEGES;
```

## SSL/HTTPS

CapRover automatically handles SSL certificates via Let's Encrypt. Just enable HTTPS in your app settings.

## Monitoring

- Check application logs in CapRover dashboard
- Monitor database connections and performance
- Set up uptime monitoring (e.g., UptimeRobot, Pingdom)

## Troubleshooting

### Application not starting

Check the logs:
```bash
caprover logs -a vier-op-een-rij
```

### Database connection errors

Verify environment variables are set correctly and the database is accessible from the app container.

### Assets not loading

Ensure `APP_URL` is set to your production domain and that the Vite build completed successfully.

## Rollback

To rollback to a previous version:

```bash
caprover deploy --imageName captain/vier-op-een-rij:previous-tag
```

## Performance Optimization

- Enable OPcache (already configured in Dockerfile)
- Use Redis for cache/sessions (optional, requires Redis container)
- Enable HTTP/2 in CapRover
- Set up CDN for static assets (optional)
