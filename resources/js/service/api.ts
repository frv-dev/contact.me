import axios from 'axios';

export interface IResponse<IResponseData> {
  error: boolean,
  message: string|null,
  developerMessage: string|null,
  data: IResponseData,
  exception: null|object,
}

export default (function () {
  const baseUrlElement = document.getElementById('base-url') as HTMLInputElement;
  const baseUrl = baseUrlElement.value;

  return axios.create({
    baseURL: baseUrl,
    timeout: 10000,
  });
})();
