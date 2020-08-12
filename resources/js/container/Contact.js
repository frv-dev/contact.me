import React from 'react';
import ReactDOM from 'react-dom';

function Contact() {
  return (
    <div>
      <h1>Contact.me</h1>
    </div>
  );
}

export default Contact;

if (document.getElementById('contact')) {
  ReactDOM.render(<Contact />, document.getElementById('contact'));
}
