import { h, render, Component } from 'preact';
import Car from './Car';

export default class CarTable extends Component {

  constructor(props) {
    super(props);

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
    let carCommand = command;

    if(command.toLowerCase().includes('place')) {
      const [, coordinates] = command.split(' ');
      let [x, y, direction] = coordinates.split(',');
      y--;
      x = x.toLowerCase().charCodeAt(0) - 97;
      carCommand = `PLACE ${x},${y},${direction}`;
    }

    fetch(`/api/car/run-command?command=${carCommand}`)
      .then(response => response.json())
      .then((jsonResponse) => {
        this.setState({
          x: jsonResponse.x,
          y: jsonResponse.y,
          direction: jsonResponse.direction
        });
      });
  }

  render(props, state) {
    const { width, length } = props;
    const {x, y, direction} = state;

    return (
      <div>
        <div class="command">
          <input class="input" type="text" placeholder="Type command..." onChange={this.changeCommand.bind(this)}/>
          <button class="button" onClick={this.runCommand.bind(this)}>RUN</button>
        </div>
        <table class="table is-bordered">
          {
            [...Array(length + 1)].map((a, i) => (
              <tr>
                {[...Array(width + 1)].map((a, j) => {
                  if(j == 0 && i != length) {
                    return <th class="cell-label">{length - i}</th>
                  } else if(i == width && j != 0) {
                    return <th class="cell-label">{String.fromCharCode(97 + j - 1)}</th>
                  } else if(x !== undefined && y !== undefined) {
                    if(length - i - 1 == y && j - 1 == x) {
                      return <td><Car direction={direction} /></td>
                    }
                  }
                  return <td></td>
                })}
              </tr>
            ))
          }
        </table>
      </div>
    );
  }
}
