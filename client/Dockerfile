FROM node:lts-alpine

WORKDIR /app

COPY package*.json ./
COPY vite.config.js ./

RUN npm install

COPY . .

RUN npm run build

EXPOSE 7159

##CMD [ "npm", "run", "dev", "--", "--host", "0.0.0.0" ]
CMD [ "npm", "run", "dev"]
