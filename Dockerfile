FROM php:8.2-apache

# Installer les dépendances nécessaires pour PostgreSQL et autres extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activer les modules Apache (si nécessaire pour Symfony)
RUN a2enmod rewrite

# Copier le contenu du projet dans le conteneur
COPY . /var/www/html

# Fixer les droits d'accès
# RUN chown -R www-data:www-data /var/www/html

# Exposer le port
EXPOSE 80
