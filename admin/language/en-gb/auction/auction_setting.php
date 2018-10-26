<?php
// Heading
$_['heading_title']                 = 'Auction Settings';


// Text
$_['text_edit']                     =   'Edit Auction Settings';
$_['text_auction']                  =   'General Auction Settings';
$_['text_auction_status']           =   'Auction Status Settings';
$_['text_auction_options']          =   'Auction Options';
$_['text_auction_display']          =   'Auction Display Options';
$_['text_auction_gallery']          =   'Gallery Settings';
$_['text_success']                  =   'Auction Settings Updated';

// Tabs
$_['tab_auction']                   =   'Auction';
$_['tab_display']                   =   'Display';
$_['tab_option']                    =   'Options';

// Entry for Auction Tab
$_['entry_limit_admin']             =   'Default Auctions Per Page (Admin)';
$_['entry_auction_count']           =   'Category Auction Count';
$_['entry_auction_dutch']           =   'Allow Dutch Auctions';
$_['entry_auction_extension']       =   'Automatically extend an auction';
$_['entry_auction_extension_left']  =   'Time left in Auction';
$_['entry_auction_extension_for']   =   'Time to extend Auction for';
$_['entry_auction_countdown']           =   'Automatically start a countdown timer';
$_['entry_auction_countdown_time']      =   'Time, in hours, to start a countdown timer';
$_['entry_auction_open_status']         =   'Auction Open Status';
$_['entry_auction_created_status']         =   'Auction Created Status';
$_['entry_auction_closed_status']       =   'Auction Closed Status';
$_['entry_auction_suspended_status']    =   'Auction Suspended Status';
$_['entry_auction_moderation_status']   =   'Auction Under Moderation Status';


// Help for Auction Tab
$_['help_limit_admin']              =   'Determines how many admin items are shown per page (auctions, customers, etc).';
$_['help_auction_count']            =   'Show the number of auctions inside the subcategories in the storefront header category menu. Be warned, this will cause an extreme performance hit for stores with a lot of subcategories!';
$_['help_auction_dutch']            =   'What is a Dutch Auction?
A Dutch auction is a public offering auction structure in which the price of the offering is set after taking in all bids to determine the highest price at which the total offering can be sold.
In this type of auction, investors place a bid for the amount they are willing to buy in terms of quantity and price.
A Dutch auction also refers to a type of auction in which the price on an item is lowered until it gets a bid.
The first bid made is the winning bid and results in a sale, assuming that the price is above the reserve price.
This is in contrast to typical options, where the price rises as bidders compete.
Read more: Dutch Auction https://www.investopedia.com/terms/d/dutchauction.asp#ixzz5STlsr4rv 
Follow us: Investopedia on Facebook';
$_['help_auction_extension']        =   'Automatically extend and auction if someone places a bid in the last moments of an auction.  This is to prevent snippers.';
$_['help_auction_extension_left']   =   'How much time, in minutes, before the end of an auction before this takes effect?';
$_['help_auction_extension_for']    =   'How much time, in minutes, shall be added to the end of an auction?';
$_['help_auction_countdown']        =   'Automatically start a countdown timer before an auction ends.';
$_['help_auction_countdown_time']   =   'How many hours before the auction ends to start the countdown.';
$_['help_auction_open_status']      =   'Set this to Open Status';
$_['help_auction_created_status']      =   'Set this to Created Status.  For Auctions created but not yet open.';
$_['help_auction_closed_status']    =   'Set this to Closed Status';
$_['help_auction_suspended_status'] =   'Set this to Suspended Status';
$_['help_auction_moderation_status']=   'Set this to Under Moderation Status';



// Entry for Display Tab
$_['entry_auction_picture_gallery'] = 'Sellers can upload extra pictures for their auctions';
$_['entry_auction_max_gallery_pictures'] = 'Maximum # of extra pictures per auction';
$_['entry_auction_max_picture_size'] = 'Maximum size of pictures';

// Help for Display Tab
$_['help_auction_picture_gallery'] = 'Allow sellers to upload extra pictures for their auctions, they will always be able to upload one picture, this is extra pictures';
$_['help_auction_max_gallery_pictures'] = 'Enter the maximum number of extra pictures per auction the seller can upload, besides the main picture';
$_['help_auction_max_picture_size'] = 'Enter the maximum size, in kb, of each picture the seller can upload';


// Entry for Option Tab
$_['entry_auction_proxy']               =   'Allow Proxy Bidding';
$_['entry_auction_custom_start_date']   =   'Allow Custom Start Dates';
$_['entry_auction_custom_end_date']     =   'Allow Custom End Dates';
$_['entry_auction_bid_increments']      =   'Allow Custom Bid Increments';
$_['entry_auction_subtitles']           =   'Allow Subtiles';
$_['entry_auction_additional_cat']      =   'Allow Additional Categories';
$_['entry_auction_auto_relist']         =   'Allow Auto Relisting';
$_['entry_auction_max_relists']         =   'Maximum # Relists';

// Help for Option Tab
$_['help_auction_proxy']                =   'Allow bidders to place a proxy bid';
$_['help_auction_custom_start_date']    =   'Allow auctions to be set up with custom starting date and times';
$_['help_auction_custom_end_date']      =   'Allow auctions to be set up with custom ending date and times';
$_['help_auction_bid_increments']       =   'Allow sellers to create their own custom bid increments';
$_['help_auction_subtitles']            =   'Allow sellers to place subtitles on their auctions';
$_['help_auction_additional_cat']       =   'Allow sellers to place their auctions in additional categories';
$_['help_auction_auto_relist']          =   'Allow sellers to auto relist their auctions if they do not get a satisfactory winning bid';
$_['help_auction_max_relists']          =   'Set the maximum number to times a seller can auto relist their auctions for';


// Error
$_['error_general']              =   'You have not completed the setting correctly.  Please recheck your settings!';
$_['error_limit']                   =   'Limit required!';
$_['error_auction_extension_left']  =   'Time is required if you want to use this feature!';
$_['error_auction_extension_for']   =   'Time is required if you want to use this feature!';
$_['error_auction_countdown_time']  =   'Time is required if you want to use this feature!';
$_['error_auction_max_relists']     =   'Must Enter a number if you are going to use this feature!';
$_['error_auction_max_gallery_pictures'] = 'Must enter the number of pictures to limit the seller!';
$_['error_auction_max_picture_size'] = 'Must enter the maximum size the pictures can be to limit the seller!';







// Debugging
$_['debugging']                     = 'Language file loaded successfully';