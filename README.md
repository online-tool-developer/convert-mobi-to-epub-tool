# convert-mobi-to-epub-tool
This project consists of a simple tool that helps convert books from .MOBI format into .EPUB using the command "ebook-convert" provided by the [Calibre](https://calibre-ebook.com/) software. 
The conversion is made by a web service written in php that can be called directly from a multipart form-data post request or from the form provided in the index.html file of this project.

# Requirements
This particular project runs on a linux server and uses the version 3.48.0 of Calibre for Linux, but you can use a most recent version depending on the characteristics of your server. Both, the 64-bit binary code and source code are included, without modifications, on this repository. They are also available at this link: https://download.calibre-ebook.com/3.48.0/ 

Install the version that better suits your needs from https://calibre-ebook.com/download 

# Web Service
The web service expects a multipart form-data post request, that would include the next information in order to work:

**$_POST['key']**: 
You can define the value of this string in order to know the source of the request, the important thing to do is to add all your valid keys at the $allowedKeys array defined at the start of the web service file. 

**$_FILES['file']**: 
The book on .MOBI format

The response will be a JSON with the next properties:

- error: boolean
- msg: string 
- url: url of the EPUB book file to download (only in case of success)

