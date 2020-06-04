import '../css/app.scss';
import 'bulma/bulma.sass';
import { h, render, Component } from 'preact';
import CarTable from './components/CarTable';

class App extends Component {
  render() {
    return (
      <div>
        <section class="hero is-light">
        <div class="hero-body">
          <div class="container">
            <h1 class="title">
              Toy Car
            </h1>
          </div>
        </div>
      </section>
      <div class="container">
        <CarTable length={5} width={5} />
      </div>
      </div>
    );
  }
}

render(<App />, document.getElementById('main'));