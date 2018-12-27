BX.ready(function () {
	BX.namespace("BX.Report.View");

	//region Horisontal scrolling
	if(typeof BX.Report.View.HScrollFader === "undefined")
	{
		BX.Report.View.HScrollFader = function(hScroll)
		{
			BX.Report.View.HScrollFader.superclass.constructor.apply(this, [hScroll]);
		};
		BX.extend(BX.Report.View.HScrollFader, BX.Grid.Fader);
		BX.Report.View.HScrollFader.prototype.adjustEarOffset = function(prepare)
		{
			if (prepare)
			{
				this.windowHeight = BX.height(window);
				this.tbodyPos = BX.pos(this.table.tBodies[0]);
				this.headerPos = BX.pos(this.table.tHead);
			}

			var scrollY = window.scrollY;

			if (this.parent.isIE())
			{
				scrollY = document.documentElement.scrollTop;
			}

			var posTop = 0;
			var posBottom = 0;

			if (!(scrollY > this.tbodyPos.bottom || (scrollY + this.windowHeight) < this.headerPos.top))
			{
				if (scrollY > this.headerPos.top)
				{
					posTop = scrollY - this.headerPos.top;
				}
				else
				{
					posTop = 0;
				}

				if (scrollY + this.windowHeight > this.tbodyPos.bottom)
				{
					posBottom = this.tbodyPos.bottom - posTop - this.headerPos.top;
				}
				else
				{
					posBottom = scrollY + this.windowHeight - posTop - this.headerPos.top;
				}
			}

			BX.Grid.Utils.requestAnimationFrame(BX.proxy(function() {
				if (posTop !== this.lastPosTop)
				{
					var translate = 'translate3d(0px, ' + posTop + 'px, 0)';
					this.getEarLeft().style.transform = translate;
					this.getEarRight().style.transform = translate;
				}

				if (posBottom !== this.lastBottomPos)
				{
					this.getEarLeft().style.height = posBottom + 'px';
					this.getEarRight().style.height = posBottom + 'px';
				}

				this.lastPosTop = posTop;
				this.lastBottomPos = posBottom;
			}, this));
		}
	}

	if(typeof BX.Report.View.HScroll === "undefined")
	{
		BX.Report.View.HScroll = function()
		{
			this._id = "";
			this.settings = new BX.Report.View.Settings({});
			this.params = {};

			this.ie = null;
			this.touch = null;
		};

		BX.Report.View.HScroll.prototype =
			{
				initialize: function(id, settings)
				{
					this._id = BX.type.isNotEmptyString(id) ? id : BX.util.getRandomString(4);
					this.settings = new BX.Report.View.Settings(settings ? settings : {});
					this.params = {};
					this.params["ALLOW_HORIZONTAL_SCROLL"] = this.settings.get("allowHorizontalScroll", false);
					this.params["ALLOW_PIN_HEADER"] = this.settings.get("allowPinHeader", false);

					if (this.getParam('ALLOW_HORIZONTAL_SCROLL'))
					{
						this.fader = new BX.Report.View.HScrollFader(this);
					}
				},
				getId: function()
				{
					return this._id;
				},
				getParam: function(name, defaultval)
				{
					return this.params.hasOwnProperty(name) ? this.params[name] : defaultval;
				},
				getTable: function ()
				{
					var result = null;

					var tableId = this.settings.get("tableId", "");
					if (BX.type.isNotEmptyString(tableId))
					{
						result = BX(tableId);
					}

					return result;
				},
				getContainer: function ()
				{
					var result = null;

					var table = this.getTable();

					if (BX.type.isDomNode(table))
					{
						result = table.parentNode;

						if (BX.type.isDomNode(result))
						{
							result = result.parentNode;
						}
						else
						{
							result = null;
						}
					}

					return result;
				},
				isIE: function()
				{
					if (!BX.type.isBoolean(this.ie))
					{
						this.ie = BX.hasClass(document.documentElement, 'bx-ie');
					}

					return this.ie;
				},
				isTouch: function()
				{
					if (!BX.type.isBoolean(this.touch))
					{
						this.touch = BX.hasClass(document.documentElement, 'bx-touch');
					}

					return this.touch;
				},
				destroy: function ()
				{
					this._id = "";
					this.settings = new BX.Report.View.Settings({});
					this.params = {};

					this.ie = null;
					this.touch = null;
				}
			};

		BX.Report.View.HScroll.prototype.getMessage = function(name)
		{
			var message = name;
			var messages = this.settings.get("messages", null);
			if (messages !== null && typeof(messages) === "object" && messages.hasOwnProperty(name))
			{
				message =  messages[name];
			}
			else
			{
				messages = BX.Report.View.HScroll.messages;
				if (messages !== null && typeof(messages) === "object" && messages.hasOwnProperty(name))
				{
					message =  messages[name];
				}
			}
			return message;
		};

		if(typeof(BX.Report.View.HScroll.messages) === "undefined")
		{
			BX.Report.View.HScroll.messages = {};
		}

		if(typeof(BX.Report.View.HScroll.items) === "undefined")
		{
			BX.Report.View.HScroll.items = {};
		}

		BX.Report.View.HScroll.create = function(id, settings)
		{
			var self = new BX.Report.View.HScroll();
			self.initialize(id, settings);
			BX.Report.View.HScroll.items[id] = self;
			return self;
		};

		BX.Report.View.HScroll.delete = function(id)
		{
			if (BX.Report.View.HScroll.items.hasOwnProperty(id))
			{
				BX.Report.View.HScroll.items[id].destroy();
				delete BX.Report.View.HScroll.items[id];
			}
		};
	}

	if(typeof BX.Report.View.Settings === "undefined")
	{
		BX.Report.View.Settings = function(settings)
		{
			this.settings = {};
			if (BX.type.isPlainObject(settings))
			{
				this.defaultSettings = settings;
			}
			else
			{
				this.defaultSettings = {};
			}
			this.prepare();
		};

		BX.Report.View.Settings.prototype = {
			prepare: function()
			{
				this.settings = this.defaultSettings;
			},

			getDefault: function()
			{
				return this.defaultSettings;
			},

			get: function(name)
			{
				var result;

				try {
					result = (this.getDefault())[name];
				} catch (err) {
					result = null;
				}

				return result;
			},

			getList: function()
			{
				return this.getDefault();
			}
		};
	}
	//endregion Horisontal scrolling
});
