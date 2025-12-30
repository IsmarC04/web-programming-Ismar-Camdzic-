FROM php:8.2-cli

WORKDIR /var/www/html

# copy frontend files into container
COPY . /var/www/html

# add start script and make executable
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose port Render expects (use PORT env, fallback 10000 in start.sh)
EXPOSE 10000

# Start the server via the start script
CMD ["start.sh"]
