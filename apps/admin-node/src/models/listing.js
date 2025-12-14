// File: apps/admin-node/src/models/listing.js

class Listing {
    constructor(listingData) {
        this.itemId = listingData.item_id || listingData.itemId;
        this.title = listingData.title;
        this.description = listingData.description;
        this.price = listingData.price;
        this.category = listingData.category;
        this.sellerId = listingData.seller_id || listingData.sellerId;
        this.buyerId = listingData.buyer_id || listingData.buyerId;
        this.status = listingData.item_status || listingData.status;
        this.createdAt = listingData.created_at || listingData.createdAt;
        this.updatedAt = listingData.updated_at || listingData.updatedAt;
    }

    
    isActive() {
        return this.status === 'active';
    }

  
    isRemoved() {
        return this.status === 'removed';
    }

 
    isSold() {
        return this.status === 'sold';
    }

   
    getFormattedPrice() {
        return `₱${parseFloat(this.price).toFixed(2)}`;
    }
}

module.exports = Listing;