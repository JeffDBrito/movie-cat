# Imagem oficial do Node
FROM node:20

# Diretório de trabalho
WORKDIR /app

# Copia os arquivos do frontend para dentro do container
COPY package*.json ./

# Instala as dependências do frontend
RUN npm install

# Copia o restante dos arquivos do frontend para dentro do container
COPY . .

# Configura a variável de ambiente para o backend
ENV VUE_APP_BACKEND_URL=http://backend-laravel:${BACKEND_PORT:-8000}

# Expõe a porta configurada no .env para o frontend
EXPOSE ${FRONTEND_PORT:-5173}

# Comando para iniciar o frontend
CMD ["npm", "run", "dev"]
