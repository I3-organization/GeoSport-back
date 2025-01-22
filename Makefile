SELF_DIR := $(dir $(lastword $(MAKEFILE_LIST)))
PROJECT_NAME = $(shell basename $(SELF_DIR))

include $(SELF_DIR)/.env
include $(SELF_DIR)/.env.local
export
include $(SELF_DIR)/.maker/Makefile.mk

ifeq ($(wildcard docker-compose.yml),)
    EXTRA_FILE =
else
    EXTRA_FILE = -f docker-compose.yml
endif

DC=docker compose -p${PROJECT_NAME} -f .maker/docker-compose.yml $(EXTRA_FILE) --env-file=.env
