# apps/admin-node/Dockerfile

# Use official Node.js LTS image
FROM node:18

# Set working directory
WORKDIR /usr/src/app

# Copy package files first (for better caching)
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the app source code
COPY . .

# Expose the port defined in your .env
EXPOSE 3000

# Start the server
CMD ["node", "server.js"]