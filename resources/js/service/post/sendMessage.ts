import api, { IResponse } from "../api";

export default async function (data: FormData): Promise<IResponse<null>> {
  const response = await api.post<IResponse<null>>('/send-message', data);

  if(!('data' in response)) throw response;
  if(response.data.error) throw response;

  return response.data;
}
