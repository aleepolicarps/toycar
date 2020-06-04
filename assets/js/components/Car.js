import { h, render, Component } from 'preact';

export default class Car extends Component {
  constructor(props) {
    super(props);
  }

  render(props, state) {
    const { direction } = props;
    let rotation = 0;
    switch(direction) {
      case 'N': rotation = 0; break;
      case 'E': rotation = 90; break;
      case 'S': rotation = 180; break;
      case 'W': rotation = 270; break;
    }

    const style = {
      transform: `rotate(${rotation}deg)`
    };

    return <img src="/images/car.png" class="car" style={style} />;
  }
}