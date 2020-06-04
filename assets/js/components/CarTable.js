import { h, render, Component } from 'preact';
import Car from './Car';

export default class CarTable extends Component {

  constructor(props) {
    super(props);

    this.setState({
      hasError: false
    });

    fetch('/api/car/status')
      .then(response => response.json())
      .then((jsonResponse) => {
        this.setState({
          x: jsonResponse.x,
          y: jsonResponse.y,
          direction: jsonResponse.direction
        });
      });

  }

  turnLeft() {
    this.setState({ rotation: this.state.rotation - 90 });
  }

  turnRight() {
    this.setState({ rotation: this.state.rotation + 90 });
  }

  move() {

  }

  changeCommand(event) {
    const {value} = event.target;
    this.setState({ command: value });
  }

  runCommand() {
    const { command } = this.state;
    let carCommand = command.trim();

    if(command.toLowerCase().includes('place')) {
      const coordinates = command.replace('PLACE', '');
      let [x, y, direction] = coordinates.replace(/ /g, '').split(',');
      y--;
      x = x.toLowerCase().charCodeAt(0) - 97;
      carCommand = `PLACE ${x},${y},${direction}`;
    }

    fetch(`/api/car/run-command?command=${carCommand}`)
      .then(response => {
        if(response.status == 403) {
          this.setState({
            hasError: true
          });

          return;
        }
        return response.json()
      })
      .then((jsonResponse) => {
        this.setState({
          x: jsonResponse.x,
          y: jsonResponse.y,
          direction: jsonResponse.direction,
          hasError: false
        });
      });
  }

  reset() {
    fetch('/api/car/reset')
    .then(response => response.json())
    .then((jsonResponse) => {
      this.setState({
        x: null,
        y: null,
        direction: null,
        hasError: false
      });
    });
  }

  runAll() {
    fetch('/api/car/run-history')
    .then(response => response.json())
    .then((jsonResponse) => {
      let seconds = 0;
      jsonResponse.forEach(({x, y, direction}) => {
        setTimeout(() => {
          this.setState({
            x: x,
            y: y,
            direction: direction
          })
        }, seconds * 300);

        seconds++;
      });
    });
  }

  render(props, state) {
    const { width, length } = props;
    const {x, y, direction, hasError} = state;

    return (
      <div>
        <div class="command">
          <input class={"input is-large " + (hasError && 'is-danger')} type="text" placeholder="Type command..." onChange={this.changeCommand.bind(this)} onKeyup={((e) => { e.keyCode == 13 && this.runCommand(e); }).bind(this)}/>
          <button class="button is-success is-large" onClick={this.runCommand.bind(this)}>RUN</button>
          <button class="button is-large is-info" onClick={this.runAll.bind(this)}>RUN ALL</button>
          <button class="button is-danger is-large" onClick={this.reset.bind(this)}>RESET</button>
        </div>
        <div class="section car-table">
          {
            [...Array(length + 1)].map((a, i) => (
              <div class="columns">
                {[...Array(width + 1)].map((a, j) => {
                  if(j == 0 && i == width) {
                    return <div class="column is-1"></div>
                  } else if(j == 0 && i != length) {
                    return <div class="column cell-label is-1">{length - i}</div>
                  } else if(i == width && j != 0) {
                    return <div class="column cell-label">{String.fromCharCode(97 + j - 1)}</div>
                  } else if(x !== undefined && y !== undefined) {
                    if(length - i - 1 == y && j - 1 == x) {
                      return <div class="column cell"><Car direction={direction} /></div>
                    }
                  }

                  return <div class="column cell"></div>
                })}
              </div>
            ))
          }
        </div>
      </div>
    );
  }
}
