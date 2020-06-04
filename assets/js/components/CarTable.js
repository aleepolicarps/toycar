import { h, render, Component } from 'preact';

export default class CarTable extends Component {
  constructor(props) {
    super(props);
  }

  render(props, state) {
    const { width, length } = props;

    return (
     <table>
       {
         [...Array(length)].map((x, i) => (
          <tr>
            {[...Array(width)].map((y, j) => <td></td>)}
           </tr>
         ))
       }
     </table>
    );
  }
}
