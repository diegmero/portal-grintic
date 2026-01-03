<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum Technology: string implements HasLabel, HasColor, HasIcon
{
    // Lenguajes
    case PHP = 'php';
    case JAVASCRIPT = 'javascript';
    case TYPESCRIPT = 'typescript';
    case PYTHON = 'python';
    case JAVA = 'java';
    case CSHARP = 'csharp';
    case GO = 'go';
    case RUST = 'rust';
    case RUBY = 'ruby';
    
    // Frameworks Frontend
    case REACT = 'react';
    case VUE = 'vue';
    case ANGULAR = 'angular';
    case SVELTE = 'svelte';
    case NEXTJS = 'nextjs';
    case NUXT = 'nuxt';
    case TAILWIND = 'tailwind';
    case BOOTSTRAP = 'bootstrap';
    case LIVEWARE = 'livewire';
    case HTMX = 'htmx';
    case HTML = 'html';
    case CSS = 'css';
    case JQUERY = 'jquery';
    
    // Frameworks Backend
    case LARAVEL = 'laravel';
    case SYMFONY = 'symfony';
    case DJANGO = 'django';
    case FASTAPI = 'fastapi';
    case EXPRESS = 'express';
    case NESTJS = 'nestjs';
    case SPRING = 'spring';
    case DOTNET = 'dotnet';
    
    // Bases de Datos
    case MYSQL = 'mysql';
    case POSTGRESQL = 'postgresql';
    case MONGODB = 'mongodb';
    case REDIS = 'redis';
    case SQLITE = 'sqlite';
    case MARIADB = 'mariadb';
    case ELASTICSEARCH = 'elasticsearch';
    
    // Otros
    case DOCKER = 'docker';
    case KUBERNETES = 'kubernetes';
    case GIT = 'git';
    case GRAPHQL = 'graphql';
    case REST_API = 'rest_api';
    case WEBSOCKETS = 'websockets';

    public function getLabel(): string
    {
        return match($this) {
            self::PHP => 'PHP',
            self::JAVASCRIPT => 'JavaScript',
            self::TYPESCRIPT => 'TypeScript',
            self::PYTHON => 'Python',
            self::JAVA => 'Java',
            self::CSHARP => 'C#',
            self::GO => 'Go',
            self::RUST => 'Rust',
            self::RUBY => 'Ruby',
            self::REACT => 'React',
            self::VUE => 'Vue.js',
            self::ANGULAR => 'Angular',
            self::SVELTE => 'Svelte',
            self::NEXTJS => 'Next.js',
            self::NUXT => 'Nuxt',
            self::TAILWIND => 'Tailwind CSS',
            self::BOOTSTRAP => 'Bootstrap',
            self::LARAVEL => 'Laravel',
            self::SYMFONY => 'Symfony',
            self::DJANGO => 'Django',
            self::FASTAPI => 'FastAPI',
            self::EXPRESS => 'Express.js',
            self::NESTJS => 'NestJS',
            self::SPRING => 'Spring Boot',
            self::DOTNET => '.NET',
            self::MYSQL => 'MySQL',
            self::POSTGRESQL => 'PostgreSQL',
            self::MONGODB => 'MongoDB',
            self::REDIS => 'Redis',
            self::SQLITE => 'SQLite',
            self::MARIADB => 'MariaDB',
            self::ELASTICSEARCH => 'Elasticsearch',
            self::DOCKER => 'Docker',
            self::KUBERNETES => 'Kubernetes',
            self::GIT => 'Git',
            self::GRAPHQL => 'GraphQL',
            self::REST_API => 'REST API',
            self::WEBSOCKETS => 'WebSockets',
            self::HTML => 'HTML',
            self::CSS => 'CSS',
            self::JQUERY => 'jQuery',
            self::HTMX => 'HTMX',
            self::LIVEWARE => 'Livewire',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PHP, self::LARAVEL, self::SYMFONY => 'gray',
            self::JAVASCRIPT, self::TYPESCRIPT, self::REACT, self::VUE, self::ANGULAR, self::SVELTE, self::NEXTJS, self::NUXT, self::EXPRESS, self::NESTJS => 'gray',
            self::PYTHON, self::DJANGO, self::FASTAPI => 'gray',
            self::JAVA, self::SPRING => 'gray',
            self::CSHARP, self::DOTNET => 'gray',
            self::GO => 'gray',
            self::RUST => 'gray',
            self::RUBY => 'gray',
            self::TAILWIND, self::BOOTSTRAP => 'gray',
            self::MYSQL, self::POSTGRESQL, self::MONGODB, self::REDIS, self::SQLITE, self::MARIADB, self::ELASTICSEARCH => 'gray',
            self::DOCKER, self::KUBERNETES => 'gray',
            self::GIT => 'gray',
            self::GRAPHQL, self::REST_API, self::WEBSOCKETS => 'gray',
            self::HTML, self::CSS, self::JQUERY, self::HTMX, self::LIVEWARE => 'gray',
        };
    }

    public function getIcon(): string
    {
        return 'heroicon-o-code-bracket';
    }
}
