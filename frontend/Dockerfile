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

# Expõe a porta 5173
EXPOSE 5173

# Comando para iniciar o frontend
CMD ["npm", "run", "dev"]