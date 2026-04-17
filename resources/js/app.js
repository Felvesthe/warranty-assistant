import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
import rover from '@sheaf/rover';
import './globals/theme.js'; /* By Sheaf.dev */
import './components/select.js';

Alpine.plugin(rover)

Livewire.start()
