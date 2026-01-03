<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum Infrastructure: string implements HasLabel, HasColor, HasIcon
{
    // Cloud Providers
    case AWS = 'aws';
    case GOOGLE_CLOUD = 'google_cloud';
    case AZURE = 'azure';
    case DIGITAL_OCEAN = 'digital_ocean';
    case LINODE = 'linode';
    case VULTR = 'vultr';
    case HEROKU = 'heroku';
    case VERCEL = 'vercel';
    case NETLIFY = 'netlify';
    case RAILWAY = 'railway';
    
    // Hosting
    case VPS = 'vps';
    case DEDICATED_SERVER = 'dedicated_server';
    case SHARED_HOSTING = 'shared_hosting';
    case CPANEL = 'cpanel';
    case PLESK = 'plesk';
    
    // CDN & DNS
    case CLOUDFLARE = 'cloudflare';
    case FASTLY = 'fastly';
    case ROUTE53 = 'route53';
    
    // CI/CD
    case GITHUB_ACTIONS = 'github_actions';
    case GITLAB_CI = 'gitlab_ci';
    case JENKINS = 'jenkins';
    case BITBUCKET_PIPELINES = 'bitbucket_pipelines';
    
    // Otros
    case NGINX = 'nginx';
    case APACHE = 'apache';
    case SSL_LETS_ENCRYPT = 'ssl_lets_encrypt';
    case LOAD_BALANCER = 'load_balancer';
    case S3_STORAGE = 's3_storage';
    case BACKUP_SERVICE = 'backup_service';

    public function getLabel(): string
    {
        return match($this) {
            self::AWS => 'Amazon AWS',
            self::GOOGLE_CLOUD => 'Google Cloud',
            self::AZURE => 'Microsoft Azure',
            self::DIGITAL_OCEAN => 'Digital Ocean',
            self::LINODE => 'Linode',
            self::VULTR => 'Vultr',
            self::HEROKU => 'Heroku',
            self::VERCEL => 'Vercel',
            self::NETLIFY => 'Netlify',
            self::RAILWAY => 'Railway',
            self::VPS => 'VPS',
            self::DEDICATED_SERVER => 'Servidor Dedicado',
            self::SHARED_HOSTING => 'Hosting Compartido',
            self::CPANEL => 'cPanel',
            self::PLESK => 'Plesk',
            self::CLOUDFLARE => 'Cloudflare',
            self::FASTLY => 'Fastly CDN',
            self::ROUTE53 => 'AWS Route 53',
            self::GITHUB_ACTIONS => 'GitHub Actions',
            self::GITLAB_CI => 'GitLab CI/CD',
            self::JENKINS => 'Jenkins',
            self::BITBUCKET_PIPELINES => 'Bitbucket Pipelines',
            self::NGINX => 'Nginx',
            self::APACHE => 'Apache',
            self::SSL_LETS_ENCRYPT => "Let's Encrypt SSL",
            self::LOAD_BALANCER => 'Load Balancer',
            self::S3_STORAGE => 'S3 Storage',
            self::BACKUP_SERVICE => 'Servicio de Backup',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::AWS, self::ROUTE53, self::S3_STORAGE => 'gray',
            self::GOOGLE_CLOUD => 'gray',
            self::AZURE => 'gray',
            self::DIGITAL_OCEAN, self::LINODE, self::VULTR => 'gray',
            self::HEROKU => 'gray',
            self::VERCEL, self::NETLIFY, self::RAILWAY => 'gray',
            self::VPS, self::DEDICATED_SERVER, self::SHARED_HOSTING => 'gray',
            self::CPANEL, self::PLESK => 'gray',
            self::CLOUDFLARE => 'gray',
            self::FASTLY => 'gray',
            self::GITHUB_ACTIONS => 'gray',
            self::GITLAB_CI => 'gray',
            self::JENKINS => 'gray',
            self::BITBUCKET_PIPELINES => 'gray',
            self::NGINX => 'gray',
            self::APACHE => 'gray',
            self::SSL_LETS_ENCRYPT => 'gray',
            self::LOAD_BALANCER => 'gray',
            self::BACKUP_SERVICE => 'gray',
        };
    }

    public function getIcon(): string
    {
        return 'heroicon-o-server';
    }
}
