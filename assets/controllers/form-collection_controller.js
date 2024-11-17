// assets/controllers/form-collection_controller.js

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["collectionContainer"]

    static values = {
        index    : Number,
        prototype: String,
    }

    connect()
    {
        console.log(this.prototypeValue);
        if (this.collectionContainerTarget.children.length === 0) {
            this.addCollectionElement();
        }
        
    }

    addCollectionElement(event)
    {
        const item = document.createElement('li');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        
        if (this.indexValue > 0) {
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'Remove';
            removeButton.setAttribute('data-action', 'form-collection#removeCollectionElement');
            item.appendChild(removeButton);
        }


        
        this.collectionContainerTarget.appendChild(item);
        this.indexValue++;
        this.repositionButton();
    }

    repositionButton(lastItem = null) {
        const addButton = this.element.querySelector('[data-action="form-collection#addCollectionElement"]');
        if (!addButton) return;
        
         if (lastItem && lastItem !== this.collectionContainerTarget.lastElementChild) {
            lastItem.appendChild(addButton);
        } else {
            
            this.collectionContainerTarget.appendChild(addButton);
        }
    }


    removeCollectionElement(event) {
   
        const item = event.target.closest('li');
        
        item.remove();

        if (this.collectionContainerTarget.children.length > 0) {
            const lastItem = this.collectionContainerTarget.lastElementChild;
            this.repositionButton(lastItem);
        }

        this.indexValue--;
        this.repositionButton();
        }
}