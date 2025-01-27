import axios from 'axios';

const instance = axios.create({
  baseURL: 'http://localhost',
});

instance.defaults.withCredentials = true;
instance.defaults.withXSRFToken = true;

export default instance;
