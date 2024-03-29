import React, { Component } from 'react';
import Header from './Header.js';
import Footer from './Footer.js';

class DefaultLayout extends Component {
  render() {
    return (
      <div>
          {this.props.children}
        <Footer />
      </div>
    );
  }
}

export default DefaultLayout;