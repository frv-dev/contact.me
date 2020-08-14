import Axios from "axios";

interface IResponse {
  error: boolean,
  message: string,
  developerMessage: string,
  data: object,
  exception: object,
}

export default async function (data: FormData): Promise<IResponse> {
  try {
    const response = await Axios.post<IResponse>('http://localhost:8085/api/send-message', data);

    if(!('data' in response)) throw response;
    if(response.data.error) throw response;

    return response.data;
  } catch (error) {
    console.log(error);
    return error;
  }
}
