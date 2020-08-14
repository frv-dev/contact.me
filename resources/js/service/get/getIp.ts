import Axios from "axios";
import ipRegex from "../../regex/ipRegex";

export default async function (): Promise<string> {
  try {
    const response = await Axios.get<string>('https://www.cloudflare.com/cdn-cgi/trace');
    const result = response.data.match(ipRegex);
    return result![0];
  } catch (error) {
    console.log(error);
    return error;
  }
}
