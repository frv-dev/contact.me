import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import getIpService from '../service/get/getIp';

function Contact() {
  const [ip, setIp] = useState('');

  useEffect(function() {
    getIp();
  }, []);

  async function getIp(): Promise<void> {
    const response = await getIpService();
    setIp(response);
  }

  return (
    <div>
      <h1>Contact.me - {ip}</h1>
    </div>
  );
}

export default Contact;

if (document.getElementById('contact')) {
  ReactDOM.render(<Contact />, document.getElementById('contact'));
}
