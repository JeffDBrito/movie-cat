import axios from 'axios';

const axios_api = axios.create({
  baseURL: 'http://localhost:8000',
  withCredentials: true,
});

export default axios_api;