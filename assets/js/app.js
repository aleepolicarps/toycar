import '../css/app.css';
import { h, render, Component } from 'preact';
import CarTable from './components/CarTable';

class App extends Component {
  render() {
    return (
      <div>
        <h1>Toy Car</h1>
        <CarTable length={5} width={5} />
      </div>
    );
  }
}

render(<App />, document.getElementById('main'));