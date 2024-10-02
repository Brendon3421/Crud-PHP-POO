# Usa a imagem do PHP mais recente com Apache
FROM php:latest

# Instala extensões do PHP necessárias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia os arquivos do projeto para o diretório do Apache
COPY ./Public /var/www/html

# Define a pasta de trabalho
WORKDIR /var/www/html

# Expõe a porta 80 para acessar a aplicação
EXPOSE 8081
