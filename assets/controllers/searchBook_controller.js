import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        console.log('SearchBook controller connected...');
    }

    write(event) {
        this.searchBook(event.target.value);
    }

    handleSubmit(event) {
        event.preventDefault();
        event.stopPropagation();

        const inputEl = event.target.querySelector('input');
        this.searchBook(inputEl.value);
    }

    async searchBook(bookName) {
        const url = '/book/search?bookName=' + bookName;
        const response = await fetch(url);
        const json = await response.json();
        console.log('searchBook', json);
    }
}