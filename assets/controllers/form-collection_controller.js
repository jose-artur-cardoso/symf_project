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
        if (this.collectionContainerTarget.children.length === 0) {
            this.addCollectionElement();
        }
        
    }

    addCollectionElement(event)
    {
        const item = document.createElement('li');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        this.collectionContainerTarget.appendChild(item);
        this.indexValue++;
    }

    addCollectiondsdsElement(event)
    {

        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

        const item = document.createElement('li');
      
        item.innerHTML = collectionHolder
          .dataset
          .prototype
          .replace(
            /__name__/g,
            collectionHolder.dataset.index
          );
      
        collectionHolder.appendChild(item);
      
        collectionHolder.dataset.index++;
        // const item = document.createElement('li');
        // item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        
        // if (this.indexValue > 0) {
        //     const removeButton = document.createElement('button');
        //     removeButton.type = 'button';
        //     removeButton.textContent = 'Remove';
        //     removeButton.setAttribute('data-action', 'form-collection#removeCollectionElement');
        //     item.appendChild(removeButton);
        // }


        
        // this.collectionContainerTarget.appendChild(item);
        // this.indexValue++;
        // this.repositionButton();
    }

   
}