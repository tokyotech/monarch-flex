/*
Copyright (c) 2007 FlexLib Contributors.  See:
    http://code.google.com/p/flexlib/wiki/ProjectContributors

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

Modified to support regular expression matching by Andrew Spencer, February 2009
*/
package controls
{
    import controls.textClasses.ElementBoundaries;
    import controls.textClasses.Finder;
    import controls.util.ElementUtils;
    
    import flash.display.Bitmap;
    import flash.display.BitmapData;
    import flash.events.Event;
    import flash.geom.Rectangle;
    import flash.html.HTMLLoader;
    
    import mx.controls.HTML;
    
    public class HTMLHighlighter
    {   
    	/**
    	* The HTMLLoader being highlighted.
    	*/
        public var htmlLoader:HTMLLoader;
        
        
        /**
        * The HTML object we are highlighting on
        */
        public var html:HTML;
        
        /**
        * The color used to highlight strings (RGBA).
        * 
        * @default 0xffff0000 (red)
        */
        public var highlightColor:uint;
        
        /**
        * Contains the bounding rectangles of each string to be highlighted.
        */
        private var boundariesToHighlight:Array;
        
        /**
        * The Bitmap object used for drawing the highlights.  Publicly exposed so that effects can be applied if desired.
        */
        public var bitmap:Bitmap;
        
        /**
        * The drawing canvas for the Bitamp object.
        */
        private var canvas:BitmapData;
        
        /**
        * The Finder object used to search the TextField.
        */
        private var finder:Finder;
        
        /**
        * Finds & highlights strings in a text field.
        * 
        * @param textField The TextField containing the text to highlight
        * @param color The color to make the highlight (RGBA).  Default is 0xffff0000 (solid red).
        * @param xOffset If necessary, the horizontal offset of the highlight.  Useful when the TextField has some padding applied to it.  Default is 0.
        * @param yOffset If necessary, the vertical offset of the highlight.  Useful when the TextField has some padding applied to it.  Default is 0.
        */
        public function HTMLHighlighter(_html:HTML,color:uint=0xffff0000)
        {
        	this.html = _html;
            this.htmlLoader = _html.htmlLoader;
            this.highlightColor = color;
            
            this.boundariesToHighlight = new Array();
            
            this.canvas = new BitmapData(2000,2000,true,0x00000000);
            
            this.bitmap = new Bitmap(canvas);
            
            // var ind:int = this.htmlLoader.parent.getChildIndex(this.htmlLoader);
            this.html.addChild(this.bitmap);
            
            finder = new Finder(this.htmlLoader);
            
            this.htmlLoader.addEventListener(Event.SCROLL,onScroll,false,3,true);
            this.htmlLoader.parent.addEventListener(Event.RESIZE,onScroll,true,2,true); 
            
        }
        
        /**
        * Fired when the TextField is scrolled.  Clears all highlights and recalculates which characters are visible.
        */
        private function onScroll(evt:Event):void{
            this.clearAllHighlights();
            this.highlightVisibleBoundaries();
        }
        
        /**
        * Resets the Highlighter, clearing all visible highlights and emptying the array of character boundaries.
        */
        public function reset():void{
            this.clearAllHighlights();
            this.boundariesToHighlight = [];
        }
        
        /**
        * Erases all highlights from the bitmap canvas.
        */
        private function clearAllHighlights():void{
            this.canvas.fillRect(new Rectangle(0,0,2000,2000),0x00000000);
        }
        
        /**
        * Traverses the boundariesToHighlight array, tests each rectangle contained therein to see if it is visible within the boundaries of the TextField.  If so, draws the rectangle to the bitmap canvas.
        */
        private function highlightVisibleBoundaries():void{
            var len:int = this.boundariesToHighlight.length;
            trace("Highlighting "+len+" matches");
            for(var i:int=0; i<len; i++){
            	var str:ElementBoundaries = this.boundariesToHighlight[i];
            	if(str.isVisible){
            		var rects:Array = str.rectangles;
            		var len2:int = rects.length;
            		for(var j:int=0; j<len2; j++){
            			var rect:Rectangle = rects[j];
			            this.canvas.fillRect(rect,this.highlightColor);
            		}
            	}
            	trace("Rect "+rects[0]);
            } 
        }

		/**
		* Highlights all instances of a string.
		* 
		* @param regex The regex to find and highlight.
		*/
        public function highlightMatches(regex:RegExp):void{         
            // Create an array of HTML elements that match
            // Changed: APS
            var elements:Array = this.finder.matchesOf(regex);
            
            for(var i:int = 0; i<elements.length; i++){
                var bounds:ElementBoundaries = new ElementBoundaries(this.htmlLoader, ElementUtils.getX(elements[i]), ElementUtils.getY(elements[i]), elements[i].width, elements[i].height);
                this.boundariesToHighlight.push(bounds);
            }
        
            this.highlightVisibleBoundaries();
        }
    }
}