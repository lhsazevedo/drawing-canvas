FROM node:22-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    && rm -rf /var/lib/apt/lists/*

USER node

CMD ["npm", "run", "dev"]
