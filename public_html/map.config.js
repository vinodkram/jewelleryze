// pm2.config.js

module.exports = {
  apps: [
    {
      name: 'jewelleryze.com', // Change this to your application's name
      script: 'npm',
      args: 'start',
      cwd: __dirname,
      instances: 'max',
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      env: {
        NODE_ENV: 'production',
      },
    },
  ],
};
